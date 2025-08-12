<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\TimezoneRequest;
use App\Http\Resources\System\TimezoneResource;
use App\Services\System\TimezoneService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Timezone
 */
class TimezoneController extends Controller
{
    protected TimezoneService $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * Display a paginated listing of the Timezone resources.
     *
     * @param Request $request
     * @return TimezoneResource|JsonResponse
     */
    public function index(Request $request): TimezoneResource|JsonResponse
    {
        $timezones = $this->timezoneService->getTimezones($request);
        return Response::paginated(TimezoneResource::collection($timezones), 'Timezones retrieved successfully.');
    }

    /**
     * Store a newly created Timezone resource in storage.
     *
     * @param TimezoneRequest $request The validated request.
     * @return TimezoneResource|JsonResponse
     */
    public function store(TimezoneRequest $request): TimezoneResource|JsonResponse
    {
        try {
            $timezone = $this->timezoneService->createTimezone($request->validated());
            return Response::created(new TimezoneResource($timezone->load(['createdBy'])), 'Timezone created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create timezone: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Timezone resource.
     *
     * @param int $id The Timezone ID.
     * @return TimezoneResource|JsonResponse
     */
    public function show(int $id): TimezoneResource|JsonResponse
    {
        try {
            $timezone = $this->timezoneService->findTimezone($id);
            return Response::success(new TimezoneResource($timezone), 'Timezone retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Timezone not found.');
        }
    }

    /**
     * Update the specified Timezone resource in storage.
     *
     * @param TimezoneRequest $request The validated request.
     * @param int $id The Timezone ID.
     * @return TimezoneResource|JsonResponse
     */
    public function update(TimezoneRequest $request, int $id): TimezoneResource|JsonResponse
    {
        try {
            $timezone = $this->timezoneService->findTimezone($id);
            $updatedTimezone = $this->timezoneService->updateTimezone($timezone, $request->validated());
            return Response::updated(new TimezoneResource($updatedTimezone->load(['createdBy', 'updatedBy'])), 'Timezone updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Timezone not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update timezone: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Timezone resource from storage (soft delete).
     *
     * @param int $id The Timezone ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $timezone = $this->timezoneService->findTimezone($id);
            $this->timezoneService->deleteTimezone($timezone);
            return Response::deleted('Timezone moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Timezone not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete timezone: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Timezone resource from trash.
     *
     * @param int $id The Timezone ID.
     * @return TimezoneResource|JsonResponse
     */
    public function restore(int $id): TimezoneResource|JsonResponse
    {
        try {
            $timezone = $this->timezoneService->restoreTimezone($id);
            return Response::success(new TimezoneResource($timezone->load(['createdBy', 'updatedBy'])), 'Timezone restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Timezone not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore timezone: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Timezone resource from storage.
     *
     * @param int $id The Timezone ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->timezoneService->forceDeleteTimezone($id);
            return Response::deleted('Timezone permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Timezone not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete timezone: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete timezones (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:timezones,id'
        ]);

        try {
            $deletedCount = $this->timezoneService->bulkDeleteTimezones($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} timezones moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No timezones found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete timezones: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore timezones from trash.
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
            $restoredCount = $this->timezoneService->bulkRestoreTimezones($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} timezones restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No timezones found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore timezones: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete timezones.
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
            $deletedCount = $this->timezoneService->bulkForceDeleteTimezones($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} timezones permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No timezones found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete timezones: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed timezones.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->timezoneService->emptyTimezoneTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} timezones permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for timezones.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->timezoneService->getTimezoneStatistics();
            return Response::success($stats, 'Timezone statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve timezone statistics: ' . $e->getMessage(), 500);
        }
    }
}
