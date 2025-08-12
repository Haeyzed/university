<?php

namespace App\Services\Web;

use App\Helpers\SlugHelper;
use App\Models\Web\Page;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of pages.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPages(Request $request): LengthAwarePaginator
    {
        return Page::query()
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
     * Create a new page.
     *
     * @param array $data
     * @param Request $request
     * @return Page
     * @throws Exception
     */
    public function createPage(array $data, Request $request): Page
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'page');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $slug = SlugHelper::generateUniqueSlug($data['slug'] ?? $data['title'], Page::class);

            $page = new Page($data);
            $page->slug = $slug;
            $page->status = $data['status'] ?? true;

            if ($request->boolean('generate_thumbnails')) {
                $sizes = ['thumb' => [150, 150], 'medium' => [400, 300], 'large' => [800, 600]];
                $uploads = $this->uploadImageWithSizes($request, 'attach', 'page', $sizes);
                $page->attach = $uploads['original'] ?? null;
                // $page->thumbnails = json_encode($uploads); // Uncomment if 'thumbnails' column exists
            } else {
                $page->attach = $this->uploadImage($request, 'attach', 'page', 1200, 600);
            }

            if (auth()->check()) {
                $page->created_by = auth()->id();
            }
            $page->save();

            return $page;
        });
    }

    /**
     * Find a page by ID or slug.
     *
     * @param int $id
     * @return Page
     */
    public function findPage(int $id): Page
    {
        return Page::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing page.
     *
     * @param Page $page
     * @param array $data
     * @param Request $request
     * @return Page
     * @throws Exception
     */
    public function updatePage(Page $page, array $data, Request $request): Page
    {
        return DB::transaction(function () use ($page, $data, $request) {
            $slug = $data['slug'] ?? $data['title'];
            if ($slug !== $page->slug) {
                $page->slug = SlugHelper::generateUniqueSlug($slug, Page::class, $page->id);
            }

            $page->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'page', 1200, 600, $page, 'attach');
            $page->attach = $imageName;

            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }
            $page->save();

            return $page;
        });
    }

    /**
     * Soft delete a page.
     *
     * @param Page $page
     * @return bool|null
     * @throws Exception
     */
    public function deletePage(Page $page): ?bool
    {
        return DB::transaction(fn() => $page->delete());
    }

    /**
     * Restore a soft-deleted page.
     *
     * @param string $id
     * @return Page
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restorePage(string $id): Page
    {
        return DB::transaction(function () use ($id) {
            $page = Page::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }
            $page->restore();

            return $page;
        });
    }

    /**
     * Permanently delete a page.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeletePage(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $page = Page::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('page', $page); // Delete associated image permanently
            return $page->forceDelete();
        });
    }

    /**
     * Bulk soft delete pages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeletePages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Page::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No pages found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore pages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestorePages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Page::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No pages found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Page::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Page::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete pages.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeletePages(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $pages = Page::onlyTrashed()->whereIn('id', $ids)->get();
            if ($pages->isEmpty()) {
                throw new ModelNotFoundException('No pages found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($pages as $item) {
                $this->deleteMedia('page', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the page trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyPageTrash(): int
    {
        return DB::transaction(function () {
            $trashedPages = Page::onlyTrashed()->get();
            if ($trashedPages->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedPages as $page) {
                $this->deleteMedia('page', $page);
                $page->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of pages.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdatePageStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Page::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No pages found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single page.
     *
     * @param Page $page
     * @return Page
     * @throws Exception
     */
    public function togglePageStatus(Page $page): Page
    {
        return DB::transaction(function () use ($page) {
            $page->status = !$page->status;
            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }
            $page->save();
            return $page;
        });
    }

    /**
     * Duplicate a page.
     *
     * @param Page $page
     * @return Page
     * @throws Exception
     */
    public function duplicatePage(Page $page): Page
    {
        return DB::transaction(function () use ($page) {
            $duplicatedPage = $page->replicate();
            $duplicatedPage->title = $page->title . ' (Copy)';
            $duplicatedPage->slug = SlugHelper::generateUniqueSlug($duplicatedPage->title, Page::class);
            $duplicatedPage->status = false;

            if (auth()->check()) {
                $duplicatedPage->created_by = auth()->id();
                $duplicatedPage->updated_by = null;
            }
            $duplicatedPage->save();
            return $duplicatedPage;
        });
    }

    /**
     * Get statistics for pages.
     *
     * @return array
     */
    public function getPageStatistics(): array
    {
        return [
            'total' => Page::query()->count(),
            'active' => Page::query()->where('status', true)->count(),
            'inactive' => Page::query()->where('status', false)->count(),
            'trashed' => Page::onlyTrashed()->count(),
            'this_month' => Page::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Page::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Page::query()->whereDate('created_at', today())->count(),
        ];
    }
}
