<?php

namespace App\Services\Web;

use App\Helpers\SlugHelper;
use App\Models\Web\Testimonial;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimonialService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of testimonials.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getTestimonials(Request $request): LengthAwarePaginator
    {
        return Testimonial::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhereLike('designation', "%{$search}%")
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
     * Create a new testimonial.
     *
     * @param array $data
     * @param Request $request
     * @return Testimonial
     * @throws Exception
     */
    public function createTestimonial(array $data, Request $request): Testimonial
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'testimonial');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }
            $testimonial = new Testimonial($data);
            $testimonial->status = $data['status'] ?? true;

            if ($request->boolean('generate_thumbnails')) {
                $sizes = ['thumb' => [150, 150], 'medium' => [400, 300], 'large' => [800, 600]];
                $uploads = $this->uploadImageWithSizes($request, 'attach', 'testimonial', $sizes);
                $testimonial->attach = $uploads['original'] ?? null;
                // $testimonial->thumbnails = json_encode($uploads); // Uncomment if 'thumbnails' column exists
            } else {
                $testimonial->attach = $this->uploadImage($request, 'attach', 'testimonial', 1200, 600);
            }

            if (auth()->check()) {
                $testimonial->created_by = auth()->id();
            }
            $testimonial->save();

            return $testimonial;
        });
    }

    /**
     * Find a testimonial by ID.
     *
     * @param int $id
     * @return Testimonial
     */
    public function findTestimonial(int $id): Testimonial
    {
        return Testimonial::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing testimonial.
     *
     * @param Testimonial $testimonial
     * @param array $data
     * @param Request $request
     * @return Testimonial
     * @throws Exception
     */
    public function updateTestimonial(Testimonial $testimonial, array $data, Request $request): Testimonial
    {
        return DB::transaction(function () use ($testimonial, $data, $request) {
            $testimonial->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'testimonial', 1200, 600, $testimonial, 'attach');
            $testimonial->attach = $imageName;

            if (auth()->check()) {
                $testimonial->updated_by = auth()->id();
            }
            $testimonial->save();

            return $testimonial;
        });
    }

    /**
     * Soft delete a testimonial.
     *
     * @param Testimonial $testimonial
     * @return bool|null
     * @throws Exception
     */
    public function deleteTestimonial(Testimonial $testimonial): ?bool
    {
        return DB::transaction(fn() => $testimonial->delete());
    }

    /**
     * Restore a soft-deleted testimonial.
     *
     * @param string $id
     * @return Testimonial
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreTestimonial(string $id): Testimonial
    {
        return DB::transaction(function () use ($id) {
            $testimonial = Testimonial::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $testimonial->updated_by = auth()->id();
            }
            $testimonial->restore();

            return $testimonial;
        });
    }

    /**
     * Permanently delete a testimonial.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteTestimonial(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $testimonial = Testimonial::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('testimonial', $testimonial); // Delete associated image permanently
            return $testimonial->forceDelete();
        });
    }

    /**
     * Bulk soft delete testimonials.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteTestimonials(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Testimonial::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No testimonials found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore testimonials.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreTestimonials(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Testimonial::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No testimonials found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Testimonial::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Testimonial::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete testimonials.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteTestimonials(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $testimonials = Testimonial::onlyTrashed()->whereIn('id', $ids)->get();
            if ($testimonials->isEmpty()) {
                throw new ModelNotFoundException('No testimonials found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($testimonials as $item) {
                $this->deleteMedia('testimonial', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the testimonial trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyTestimonialTrash(): int
    {
        return DB::transaction(function () {
            $trashedTestimonials = Testimonial::onlyTrashed()->get();
            if ($trashedTestimonials->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedTestimonials as $testimonial) {
                $this->deleteMedia('testimonial', $testimonial);
                $testimonial->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of testimonials.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateTestimonialStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Testimonial::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No testimonials found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single testimonial.
     *
     * @param Testimonial $testimonial
     * @return Testimonial
     * @throws Exception
     */
    public function toggleTestimonialStatus(Testimonial $testimonial): Testimonial
    {
        return DB::transaction(function () use ($testimonial) {
            $testimonial->status = !$testimonial->status;
            if (auth()->check()) {
                $testimonial->updated_by = auth()->id();
            }
            $testimonial->save();
            return $testimonial;
        });
    }

    /**
     * Duplicate a testimonial.
     *
     * @param Testimonial $testimonial
     * @return Testimonial
     * @throws Exception
     */
    public function duplicateTestimonial(Testimonial $testimonial): Testimonial
    {
        return DB::transaction(function () use ($testimonial) {
            $duplicatedTestimonial = $testimonial->replicate();
            $duplicatedTestimonial->name = $testimonial->name . ' (Copy)';
            $duplicatedTestimonial->status = false;

            if (auth()->check()) {
                $duplicatedTestimonial->created_by = auth()->id();
                $duplicatedTestimonial->updated_by = null;
            }
            $duplicatedTestimonial->save();
            return $duplicatedTestimonial;
        });
    }

    /**
     * Get statistics for testimonials.
     *
     * @return array
     */
    public function getTestimonialStatistics(): array
    {
        return [
            'total' => Testimonial::query()->count(),
            'active' => Testimonial::query()->where('status', true)->count(),
            'inactive' => Testimonial::query()->where('status', false)->count(),
            'trashed' => Testimonial::onlyTrashed()->count(),
            'this_month' => Testimonial::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Testimonial::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Testimonial::query()->whereDate('created_at', today())->count(),
        ];
    }
}
