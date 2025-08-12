<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\StateRequest;
use App\Http\Resources\System\StateResource;
use App\Services\System\StateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags State
 */
class StateController extends Controller
{
    protected StateService $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * Display a paginated listing of the State resources.
     *
     * @param Request $request
     * @return StateResource|JsonResponse
     */
    public function index(Request $request): StateResource|JsonResponse
    {
        $states = $this->stateService->getStates($request);
        return Response::paginated(StateResource::collection($states), 'States retrieved successfully.');
    }

    /**
     * Store a newly created State resource in storage.
     *
     * @param StateRequest $request The validated request.
     * @return StateResource|JsonResponse
     */
    public function store(StateRequest $request): StateResource|JsonResponse
    {
        try {
            $state = $this->stateService->createState($request->validated());
            return Response::created(new StateResource($state->load(['createdBy'])), 'State created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create state: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified State resource.
     *
     * @param int $id The State ID.
     * @return StateResource|JsonResponse
     */
    public function show(int $id): StateResource|JsonResponse
    {
        try {
            $state = $this->stateService->findState($id);
            return Response::success(new StateResource($state), 'State retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('State not found.');
        }
    }

    /**
     * Update the specified State resource in storage.
     *
     * @param StateRequest $request The validated request.
     * @param int $id The State ID.
     * @return StateResource|JsonResponse
     */
    public function update(StateRequest $request, int $id): StateResource|JsonResponse
    {
        try {
            $state = $this->stateService->findState($id);
            $updatedState = $this->stateService->updateState($state, $request->validated());
            return Response::updated(new StateResource($updatedState->load(['createdBy', 'updatedBy'])), 'State updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('State not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update state: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified State resource from storage (soft delete).
     *
     * @param int $id The State ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $state = $this->stateService->findState($id);
            $this->stateService->deleteState($state);
            return Response::deleted('State moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('State not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete state: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified State resource from trash.
     *
     * @param int $id The State ID.
     * @return StateResource|JsonResponse
     */
    public function restore(int $id): StateResource|JsonResponse
    {
        try {
            $state = $this->stateService->restoreState($id);
            return Response::success(new StateResource($state->load(['createdBy', 'updatedBy'])), 'State restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('State not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore state: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified State resource from storage.
     *
     * @param int $id The State ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->stateService->forceDeleteState($id);
            return Response::deleted('State permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('State not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete state: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete states (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:states,id'
        ]);

        try {
            $deletedCount = $this->stateService->bulkDeleteStates($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} states moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No states found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete states: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore states from trash.
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
            $restoredCount = $this->stateService->bulkRestoreStates($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} states restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No states found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore states: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete states.
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
            $deletedCount = $this->stateService->bulkForceDeleteStates($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} states permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No states found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete states: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed states.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->stateService->emptyStateTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} states permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for states.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->stateService->getStateStatistics();
            return Response::success($stats, 'State statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve state statistics: ' . $e->getMessage(), 500);
        }
    }
}
