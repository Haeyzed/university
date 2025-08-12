<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\NewsRequest;
use App\Http\Resources\Web\NewsResource;
use App\Services\Web\NewsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags News
 */
class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a paginated listing of the News resources.
     *
     * @param Request $request
     * @return NewsResource|JsonResponse
     */
    public function index(Request $request): NewsResource|JsonResponse
    {
        $news = $this->newsService->getNewsArticles($request);
        return Response::paginated(NewsResource::collection($news), 'News articles retrieved successfully.');
    }

    /**
     * Store a newly created News resource in storage.
     *
     * @param NewsRequest $request The validated request.
     * @return NewsResource|JsonResponse
     */
    public function store(NewsRequest $request): NewsResource|JsonResponse
    {
        try {
            $news = $this->newsService->createNewsArticle($request->validated(), $request);
            return Response::created(new NewsResource($news->load(['createdBy'])), 'News article created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create news article: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified News resource.
     *
     * @param int $id The News ID or slug.
     * @return NewsResource|JsonResponse
     */
    public function show(int $id): NewsResource|JsonResponse
    {
        try {
            $news = $this->newsService->findNewsArticle($id);
            return Response::success(new NewsResource($news), 'News article retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found.');
        }
    }

    /**
     * Update the specified News resource in storage.
     *
     * @param NewsRequest $request The validated request.
     * @param int $id The News ID.
     * @return NewsResource|JsonResponse
     */
    public function update(NewsRequest $request, int $id): NewsResource|JsonResponse
    {
        $request->query('_method', 'PUT');
        try {
            $news = $this->newsService->findNewsArticle($id);
            Log::info($news);
            $updatedNews = $this->newsService->updateNewsArticle($news, $request->validated(), $request);
            return Response::updated(new NewsResource($updatedNews->load(['createdBy', 'updatedBy'])), 'News article updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update news article: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified News resource from storage (soft delete).
     *
     * @param int $id The News ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $news = $this->newsService->findNewsArticle($id);
            $this->newsService->deleteNewsArticle($news);
            return Response::deleted('News article moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete news article: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified News resource from trash.
     *
     * @param int $id The News ID.
     * @return NewsResource|JsonResponse
     */
    public function restore(int $id): NewsResource|JsonResponse
    {
        try {
            $news = $this->newsService->restoreNewsArticle($id);
            return Response::success(new NewsResource($news->load(['createdBy', 'updatedBy'])), 'News article restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore news article: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified News resource from storage.
     *
     * @param int $id The News ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->newsService->forceDeleteNewsArticle($id);
            return Response::deleted('News article permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete news article: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete news articles (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:news,id'
        ]);

        try {
            $deletedCount = $this->newsService->bulkDeleteNewsArticles($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} news articles moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No news articles found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete news articles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore news articles from trash.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer'
        ]);

        try {
            $restoredCount = $this->newsService->bulkRestoreNewsArticles($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} news articles restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No news articles found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore news articles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete news articles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkForceDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer'
        ]);

        try {
            $deletedCount = $this->newsService->bulkForceDeleteNewsArticles($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} news articles permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No news articles found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete news articles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed news articles.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->newsService->emptyNewsTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} news articles permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of news articles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:news,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->newsService->bulkUpdateNewsStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} news articles status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No news articles found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update news articles status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single news article.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $news = $this->newsService->findNewsArticle($id);
            $updatedNews = $this->newsService->toggleNewsStatus($news);
            $statusText = $updatedNews->status ? 'activated' : 'deactivated';
            return Response::success(new NewsResource($updatedNews), "News article {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle news article status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a news article.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function duplicate(int $id): JsonResponse
    {
        try {
            $news = $this->newsService->findNewsArticle($id);
            $duplicatedNews = $this->newsService->duplicateNewsArticle($news);
            return Response::created(new NewsResource($duplicatedNews->load(['createdBy'])), 'News article duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('News article not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate news article: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for news articles.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->newsService->getNewsStatistics();
            return Response::success($stats, 'News statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve news statistics: ' . $e->getMessage(), 500);
        }
    }
}
