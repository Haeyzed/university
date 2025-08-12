<?php

namespace App\Services\Web;

use App\Helpers\SlugHelper;
use App\Models\Web\News;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsService
{
    use FileUploader;

    /**
     * Retrieve a paginated list of news articles.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getNewsArticles(Request $request): LengthAwarePaginator
    {
        return News::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('title', "%{$search}%")
                    ->orWhereLike('short_description', "%{$search}%")
                    ->orWhereLike('long_description', "%{$search}%")
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', (bool) $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('date', '<=', $request->date_to))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'title', 'date', 'status', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new news article.
     *
     * @param array $data
     * @param Request $request
     * @return News
     * @throws Exception
     */
    public function createNewsArticle(array $data, Request $request): News
    {
        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('attach') && ($data['prevent_duplicate'] ?? true)) {
                $duplicate = $this->checkDuplicate($request->file('attach'), 'news');
                if ($duplicate) {
                    throw new Exception('A file with identical content already exists: ' . $duplicate, 409);
                }
            }

            $slug = SlugHelper::generateUniqueSlug($data['slug'] ?? $data['title'], News::class);

            $news = new News($data);
            $news->slug = $slug;
            $news->status = $data['status'] ?? true;

            if ($request->boolean('generate_thumbnails')) {
                $sizes = ['thumb' => [150, 150], 'medium' => [400, 300], 'large' => [800, 600]];
                $uploads = $this->uploadImageWithSizes($request, 'attach', 'news', $sizes);
                $news->attach = $uploads['original'] ?? null;
                // Ensure 'thumbnails' column exists in your News model/table if you uncomment this
                // $news->thumbnails = json_encode($uploads);
            } else {
                $news->attach = $this->uploadImage($request, 'attach', 'news', 800, 500);
            }

            if (auth()->check()) {
                $news->created_by = auth()->id();
            }
            $news->save();

            return $news;
        });
    }

    /**
     * Find a news article by ID.
     *
     * @param int $id
     * @return News
     */
    public function findNewsArticle(int $id): News
    {
        return News::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing news article.
     *
     * @param News $news
     * @param array $data
     * @param Request $request
     * @return News
     * @throws Exception
     */
    public function updateNewsArticle(News $news, array $data, Request $request): News
    {
        return DB::transaction(function () use ($news, $data, $request) {
            $slug = $data['slug'] ?? $data['title'];
            if ($slug !== $news->slug) {
                $news->slug = SlugHelper::generateUniqueSlug($slug, News::class, $news->id);
            }

            $news->fill($data);

            $imageName = $this->updateImage($request, 'attach', 'news', 800, 500, $news, 'attach');
            $news->attach = $imageName;

            if (auth()->check()) {
                $news->updated_by = auth()->id();
            }
            $news->save();

            return $news;
        });
    }

    /**
     * Soft delete a news article.
     *
     * @param News $news
     * @return bool|null
     * @throws Exception
     */
    public function deleteNewsArticle(News $news): ?bool
    {
        return DB::transaction(fn() => $news->delete());
    }

    /**
     * Restore a soft-deleted news article.
     *
     * @param string $id
     * @return News
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreNewsArticle(string $id): News
    {
        return DB::transaction(function () use ($id) {
            $news = News::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $news->updated_by = auth()->id();
            }
            $news->restore();

            return $news;
        });
    }

    /**
     * Permanently delete a news article.
     *
     * @param string $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteNewsArticle(string $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $news = News::onlyTrashed()->findOrFail($id);
            $this->deleteMedia('news', $news); // Delete associated image permanently
            return $news->forceDelete();
        });
    }

    /**
     * Bulk soft delete news articles.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteNewsArticles(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = News::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No news articles found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore news articles.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreNewsArticles(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = News::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No news articles found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                News::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return News::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete news articles.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteNewsArticles(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $newsArticles = News::onlyTrashed()->whereIn('id', $ids)->get();
            if ($newsArticles->isEmpty()) {
                throw new ModelNotFoundException('No news articles found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($newsArticles as $item) {
                $this->deleteMedia('news', $item);
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty the news trash.
     *
     * @return int
     * @throws Exception
     */
    public function emptyNewsTrash(): int
    {
        return DB::transaction(function () {
            $trashedNews = News::onlyTrashed()->get();
            if ($trashedNews->isEmpty()) {
                return 0; // No items to delete
            }

            $deletedCount = 0;
            foreach ($trashedNews as $news) {
                $this->deleteMedia('news', $news);
                $news->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of news articles.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateNewsStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = News::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No news articles found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single news article.
     *
     * @param News $news
     * @return News
     * @throws Exception
     */
    public function toggleNewsStatus(News $news): News
    {
        return DB::transaction(function () use ($news) {
            $news->status = !$news->status;
            if (auth()->check()) {
                $news->updated_by = auth()->id();
            }
            $news->save();
            return $news;
        });
    }

    /**
     * Duplicate a news article.
     *
     * @param News $news
     * @return News
     * @throws Exception
     */
    public function duplicateNewsArticle(News $news): News
    {
        return DB::transaction(function () use ($news) {
            $duplicatedNews = $news->replicate();
            $duplicatedNews->title = $news->title . ' (Copy)';
            $duplicatedNews->slug = SlugHelper::generateUniqueSlug($duplicatedNews->title, News::class);
            $duplicatedNews->status = false; // Set as inactive by default

            if (auth()->check()) {
                $duplicatedNews->created_by = auth()->id();
                $duplicatedNews->updated_by = null;
            }
            $duplicatedNews->save();
            return $duplicatedNews;
        });
    }

    /**
     * Get file metadata for a news article's attachment.
     *
     * @param News $news
     * @return array
     * @throws Exception
     */
    public function getNewsFileMetadata(News $news): array
    {
        if (!$news->attach) {
            throw new ModelNotFoundException('No file attached to this news article.');
        }
        return $this->getFileMetadata('news/' . $news->attach);
    }

    /**
     * Get statistics for news articles.
     *
     * @return array
     */
    public function getNewsStatistics(): array
    {
        return [
            'total' => News::query()->count(),
            'active' => News::query()->where('status', true)->count(),
            'inactive' => News::query()->where('status', false)->count(),
            'trashed' => News::onlyTrashed()->count(),
            'this_month' => News::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => News::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => News::query()->whereDate('created_at', today())->count(),
        ];
    }
}
