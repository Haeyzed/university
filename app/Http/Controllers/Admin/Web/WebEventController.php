<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\WebEventRequest;
use App\Http\Resources\Web\WebEventResource;
use App\Services\Web\WebEventService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags WebEvent
 */
class WebEventController extends Controller
{
    protected WebEventService $webEventService;

    public function __construct(WebEventService $webEventService)
    {
        $this->webEventService = $webEventService;
    }

    /**
     * Display a paginated listing of the WebEvent resources.
     *
     * @param Request $request
     * @return WebEventResource|JsonResponse
     */
    public function index(Request $request): WebEventResource|JsonResponse
    {
        $webEvents = $this->webEventService->getWebEvents($request);
        return Response::paginated(WebEventResource::collection($webEvents), 'Web Events retrieved successfully.');
    }

    /**
     * Store a newly created WebEvent resource in storage.
     *
     * @param WebEventRequest $request The validated request.
     * @return WebEventResource|JsonResponse
     */
    public function store(WebEventRequest $request): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->createWebEvent($request->validated(), $request);
            return Response::created(new WebEventResource($webEvent->load(['createdBy'])), 'Web Event created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create web event: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified WebEvent resource.
     *
     * @param int $id The WebEvent ID or slug.
     * @return WebEventResource|JsonResponse
     */
    public function show(int $id): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->findWebEvent($id);
            return Response::success(new WebEventResource($webEvent), 'Web Event retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found.');
        }
    }

    /**
     * Update the specified WebEvent resource in storage.
     *
     * @param WebEventRequest $request The validated request.
     * @param int $id The WebEvent ID.
     * @return WebEventResource|JsonResponse
     */
    public function update(WebEventRequest $request, int $id): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->findWebEvent($id);
            $updatedWebEvent = $this->webEventService->updateWebEvent($webEvent, $request->validated(), $request);
            return Response::updated(new WebEventResource($updatedWebEvent->load(['createdBy', 'updatedBy'])), 'Web Event updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update web event: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified WebEvent resource from storage (soft delete).
     *
     * @param int $id The WebEvent ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $webEvent = $this->webEventService->findWebEvent($id);
            $this->webEventService->deleteWebEvent($webEvent);
            return Response::deleted('Web Event moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete web event: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified WebEvent resource from trash.
     *
     * @param int $id The WebEvent ID.
     * @return WebEventResource|JsonResponse
     */
    public function restore(int $id): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->restoreWebEvent($id);
            return Response::success(new WebEventResource($webEvent->load(['createdBy', 'updatedBy'])), 'Web Event restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore web event: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified WebEvent resource from storage.
     *
     * @param int $id The WebEvent ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->webEventService->forceDeleteWebEvent($id);
            return Response::deleted('Web Event permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete web event: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete web events (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:web_events,id'
        ]);

        try {
            $deletedCount = $this->webEventService->bulkDeleteWebEvents($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} web events moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No web events found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete web events: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore web events from trash.
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
            $restoredCount = $this->webEventService->bulkRestoreWebEvents($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} web events restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No web events found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore web events: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete web events.
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
            $deletedCount = $this->webEventService->bulkForceDeleteWebEvents($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} web events permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No web events found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete web events: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed web events.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->webEventService->emptyWebEventTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} web events permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of web events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:web_events,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->webEventService->bulkUpdateWebEventStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} web events status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No web events found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update web events status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single web event.
     *
     * @param string $id
     * @return WebEventResource|JsonResponse
     */
    public function toggleStatus(string $id): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->findWebEvent($id);
            $updatedWebEvent = $this->webEventService->toggleWebEventStatus($webEvent);
            $statusText = $updatedWebEvent->status ? 'activated' : 'deactivated';
            return Response::success(new WebEventResource($updatedWebEvent), "Web Event {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle web event status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a web event.
     *
     * @param int $id
     * @return WebEventResource|JsonResponse
     */
    public function duplicate(int $id): WebEventResource|JsonResponse
    {
        try {
            $webEvent = $this->webEventService->findWebEvent($id);
            $duplicatedWebEvent = $this->webEventService->duplicateWebEvent($webEvent);
            return Response::created(new WebEventResource($duplicatedWebEvent->load(['createdBy'])), 'Web Event duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Web Event not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate web event: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for web events.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->webEventService->getWebEventStatistics();
            return Response::success($stats, 'Web Event statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve web event statistics: ' . $e->getMessage(), 500);
        }
    }
}
