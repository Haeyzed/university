<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\GalleryRequest;
use App\Http\Resources\Web\GalleryResource;
use App\Services\Web\GalleryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Gallery
 */
class GalleryController extends Controller
{
    protected GalleryService $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Display a paginated listing of the Gallery resources.
     *
     * @param Request $request
     * @return GalleryResource|JsonResponse
     */
    public function index(Request $request): GalleryResource|JsonResponse
    {
        $galleries = $this->galleryService->getGalleries($request);
        return Response::paginated(GalleryResource::collection($galleries), 'Gallery items retrieved successfully.');
    }

    /**
     * Store a newly created Gallery resource in storage.
     *
     * @param GalleryRequest $request The validated request.
     * @return GalleryResource|JsonResponse
     */
    public function store(GalleryRequest $request): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->createGallery($request->validated(), $request);
            return Response::created(new GalleryResource($gallery->load(['createdBy'])), 'Gallery item created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create gallery item: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Gallery resource.
     *
     * @param int $id The Gallery ID.
     * @return GalleryResource|JsonResponse
     */
    public function show(int $id): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->findGallery($id);
            return Response::success(new GalleryResource($gallery), 'Gallery item retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found.');
        }
    }

    /**
     * Update the specified Gallery resource in storage.
     *
     * @param GalleryRequest $request The validated request.
     * @param int $id The Gallery ID.
     * @return GalleryResource|JsonResponse
     */
    public function update(GalleryRequest $request, int $id): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->findGallery($id);
            $updatedGallery = $this->galleryService->updateGallery($gallery, $request->validated(), $request);
            return Response::updated(new GalleryResource($updatedGallery->load(['createdBy', 'updatedBy'])), 'Gallery item updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update gallery item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Gallery resource from storage (soft delete).
     *
     * @param int $id The Gallery ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $gallery = $this->galleryService->findGallery($id);
            $this->galleryService->deleteGallery($gallery);
            return Response::deleted('Gallery item moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete gallery item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Gallery resource from trash.
     *
     * @param int $id The Gallery ID.
     * @return GalleryResource|JsonResponse
     */
    public function restore(int $id): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->restoreGallery($id);
            return Response::success(new GalleryResource($gallery->load(['createdBy', 'updatedBy'])), 'Gallery item restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore gallery item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Gallery resource from storage.
     *
     * @param int $id The Gallery ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->galleryService->forceDeleteGallery($id);
            return Response::deleted('Gallery item permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete gallery item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete gallery items (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:galleries,id'
        ]);

        try {
            $deletedCount = $this->galleryService->bulkDeleteGalleries($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} gallery items moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No gallery items found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete gallery items: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore gallery items from trash.
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
            $restoredCount = $this->galleryService->bulkRestoreGalleries($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} gallery items restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No gallery items found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore gallery items: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete gallery items.
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
            $deletedCount = $this->galleryService->bulkForceDeleteGalleries($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} gallery items permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No gallery items found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete gallery items: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed gallery items.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->galleryService->emptyGalleryTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} gallery items permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of gallery items.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:galleries,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->galleryService->bulkUpdateGalleryStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} gallery items status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No gallery items found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update gallery items status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single gallery item.
     *
     * @param string $id
     * @return GalleryResource|JsonResponse
     */
    public function toggleStatus(string $id): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->findGallery($id);
            $updatedGallery = $this->galleryService->toggleGalleryStatus($gallery);
            $statusText = $updatedGallery->status ? 'activated' : 'deactivated';
            return Response::success(new GalleryResource($updatedGallery), "Gallery item {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle gallery item status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a gallery item.
     *
     * @param int $id
     * @return GalleryResource|JsonResponse
     */
    public function duplicate(int $id): GalleryResource|JsonResponse
    {
        try {
            $gallery = $this->galleryService->findGallery($id);
            $duplicatedGallery = $this->galleryService->duplicateGallery($gallery);
            return Response::created(new GalleryResource($duplicatedGallery->load(['createdBy'])), 'Gallery item duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Gallery item not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate gallery item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for gallery items.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->galleryService->getGalleryStatistics();
            return Response::success($stats, 'Gallery statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve gallery statistics: ' . $e->getMessage(), 500);
        }
    }
}
