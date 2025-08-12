<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\PageRequest;
use App\Http\Resources\Web\PageResource;
use App\Services\Web\PageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Page
 */
class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a paginated listing of the Page resources.
     *
     * @param Request $request
     * @return PageResource|JsonResponse
     */
    public function index(Request $request): PageResource|JsonResponse
    {
        $pages = $this->pageService->getPages($request);
        return Response::paginated(PageResource::collection($pages), 'Pages retrieved successfully.');
    }

    /**
     * Store a newly created Page resource in storage.
     *
     * @param PageRequest $request The validated request.
     * @return PageResource|JsonResponse
     */
    public function store(PageRequest $request): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->createPage($request->validated(), $request);
            return Response::created(new PageResource($page->load(['createdBy'])), 'Page created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create page: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Page resource.
     *
     * @param int $id The Page ID or slug.
     * @return PageResource|JsonResponse
     */
    public function show(int $id): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->findPage($id);
            return Response::success(new PageResource($page), 'Page retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found.');
        }
    }

    /**
     * Update the specified Page resource in storage.
     *
     * @param PageRequest $request The validated request.
     * @param int $id The Page ID.
     * @return PageResource|JsonResponse
     */
    public function update(PageRequest $request, int $id): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->findPage($id);
            $updatedPage = $this->pageService->updatePage($page, $request->validated(), $request);
            return Response::updated(new PageResource($updatedPage->load(['createdBy', 'updatedBy'])), 'Page updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Page resource from storage (soft delete).
     *
     * @param int $id The Page ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $page = $this->pageService->findPage($id);
            $this->pageService->deletePage($page);
            return Response::deleted('Page moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Page resource from trash.
     *
     * @param int $id The Page ID.
     * @return PageResource|JsonResponse
     */
    public function restore(int $id): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->restorePage($id);
            return Response::success(new PageResource($page->load(['createdBy', 'updatedBy'])), 'Page restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Page resource from storage.
     *
     * @param int $id The Page ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->pageService->forceDeletePage($id);
            return Response::deleted('Page permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete pages (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:pages,id'
        ]);

        try {
            $deletedCount = $this->pageService->bulkDeletePages($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} pages moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No pages found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete pages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore pages from trash.
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
            $restoredCount = $this->pageService->bulkRestorePages($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} pages restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No pages found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore pages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete pages.
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
            $deletedCount = $this->pageService->bulkForceDeletePages($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} pages permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No pages found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete pages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed pages.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->pageService->emptyPageTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} pages permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of pages.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:pages,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->pageService->bulkUpdatePageStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} pages status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No pages found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update pages status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single page.
     *
     * @param string $id
     * @return PageResource|JsonResponse
     */
    public function toggleStatus(string $id): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->findPage($id);
            $updatedPage = $this->pageService->togglePageStatus($page);
            $statusText = $updatedPage->status ? 'activated' : 'deactivated';
            return Response::success(new PageResource($updatedPage), "Page {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle page status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a page.
     *
     * @param int $id
     * @return PageResource|JsonResponse
     */
    public function duplicate(int $id): PageResource|JsonResponse
    {
        try {
            $page = $this->pageService->findPage($id);
            $duplicatedPage = $this->pageService->duplicatePage($page);
            return Response::created(new PageResource($duplicatedPage->load(['createdBy'])), 'Page duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Page not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for pages.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->pageService->getPageStatistics();
            return Response::success($stats, 'Page statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve page statistics: ' . $e->getMessage(), 500);
        }
    }
}
