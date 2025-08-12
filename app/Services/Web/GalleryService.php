<?php

namespace App\Services\Web;

use App\Models\Web\Gallery;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of gallery items.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getGalleries(Request $request): LengthAwarePaginator
    {
        return Gallery::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('description', "%{$search}%")
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', (bool) $request->status))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'title', 'status', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new gallery item.
     *
     * @param array $data
     * @param Request $request
     * @return Gallery
     * @throws Exception
     */
    public function createGallery(array $data, Request $request): Gallery
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'gallery');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $gallery = new Gallery($data);
            $gallery->status = $data['status'] ?? true;

            if ($request->boolean('generate_thumbnails')) {
                $sizes = ['thumb' => [150, 150], 'medium' => [400, 300], 'large' => [800, 600]];
                $uploads = $this->uploadImageWithSizes($request, 'attach', 'gallery', $sizes);
                $gallery->attach = $uploads['original'] ?? null;
                // $gallery->thumbnails = json_encode($uploads); // Uncomment if 'thumbnails' column exists
            } else {
                $gallery->attach = $this->uploadImage($request, 'attach', 'gallery', 1200, 600);
            }

            if (auth()->check()) {
                $gallery->created_by = auth()->id();
            }
            $gallery->save();

            return $gallery;
        });
    }

    /**
     * Find a gallery item by ID.
     *
     * @param int $id
     * @return Gallery
     */
    public function findGallery(int $id): Gallery
    {
        return Gallery::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing gallery item.
     *
     * @param Gallery $gallery
     * @param array $data
     * @param Request $request
     * @return Gallery
     * @throws Exception
     */
    public function updateGallery(Gallery $gallery, array $data, Request $request): Gallery
    {
        return DB::transaction(function () use ($gallery, $data, $request) {
            $gallery->fill($data);

            if ($request->boolean('attach_removed')) {
                $this->deleteMedia('gallery', $gallery, 'attach');
                $gallery->attach = null;
            } elseif ($request->hasFile('attach')) {
                $imageName = $this->updateImage($request, 'attach', 'gallery', 1200, 600, $gallery, 'attach');
                $gallery->attach = $imageName;
            }

            if (auth()->check()) {
                $gallery->updated_by = auth()->id();
            }
            $gallery->save();

            return $gallery;
        });
    }

    /**
     * Soft delete a gallery item.
     *
     * @param Gallery $gallery
     * @return bool|null
     * @throws Exception
     */
    public function deleteGallery(Gallery $gallery): ?bool
    {
        return DB::transaction(fn() => $gallery->delete());
    }

    /**
     * Restore a soft-deleted gallery item.
     *
     * @param string $id
     * @return Gallery
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreGallery(string $id): Gallery
    {
        return DB::transaction(function () use ($id) {
            $gallery = Gallery::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $gallery->updated_by = auth()->id();
            }
            $gallery->restore();

            return $gallery;
        });
    }

    /**
     * Permanently delete a gallery item.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteGallery(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $gallery = Gallery::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('gallery', $gallery); // Delete associated image permanently
            return $gallery->forceDelete();
        });
    }

    /**
     * Bulk soft delete gallery items.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteGalleries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Gallery::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No gallery items found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore gallery items.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreGalleries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Gallery::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No gallery items found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Gallery::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Gallery::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete gallery items.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteGalleries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $galleries = Gallery::onlyTrashed()->whereIn('id', $ids)->get();
            if ($galleries->isEmpty()) {
                throw new ModelNotFoundException('No gallery items found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($galleries as $item) {
                $this->deleteMedia('gallery', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the gallery trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyGalleryTrash(): int
    {
        return DB::transaction(function () {
            $trashedGalleries = Gallery::onlyTrashed()->get();
            if ($trashedGalleries->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedGalleries as $gallery) {
                $this->deleteMedia('gallery', $gallery);
                $gallery->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of gallery items.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateGalleryStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Gallery::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No gallery items found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single gallery item.
     *
     * @param Gallery $gallery
     * @return Gallery
     * @throws Exception
     */
    public function toggleGalleryStatus(Gallery $gallery): Gallery
    {
        return DB::transaction(function () use ($gallery) {
            $gallery->status = !$gallery->status;
            if (auth()->check()) {
                $gallery->updated_by = auth()->id();
            }
            $gallery->save();
            return $gallery;
        });
    }

    /**
     * Duplicate a gallery item.
     *
     * @param Gallery $gallery
     * @return Gallery
     * @throws Exception
     */
    public function duplicateGallery(Gallery $gallery): Gallery
    {
        return DB::transaction(function () use ($gallery) {
            $duplicatedGallery = $gallery->replicate();
            $duplicatedGallery->title = ($gallery->title ?? 'Untitled') . ' (Copy)';
            $duplicatedGallery->status = false;

            if (auth()->check()) {
                $duplicatedGallery->created_by = auth()->id();
                $duplicatedGallery->updated_by = null;
            }
            $duplicatedGallery->save();
            return $duplicatedGallery;
        });
    }

    /**
     * Get statistics for gallery items.
     *
     * @return array
     */
    public function getGalleryStatistics(): array
    {
        return [
            'total' => Gallery::query()->count(),
            'active' => Gallery::query()->where('status', true)->count(),
            'inactive' => Gallery::query()->where('status', false)->count(),
            'trashed' => Gallery::onlyTrashed()->count(),
            'this_month' => Gallery::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Gallery::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Gallery::query()->whereDate('created_at', today())->count(),
        ];
    }
}
