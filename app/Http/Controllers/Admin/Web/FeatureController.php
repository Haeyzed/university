<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\FeatureRequest;
use App\Http\Resources\Web\FeatureResource;
use App\Services\Web\FeatureService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Feature
 */
class FeatureController extends Controller
{
    protected FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    /**
     * Display a paginated listing of the Feature resources.
     *
     * @param Request $request
     * @return FeatureResource|JsonResponse
     */
    public function index(Request $request): FeatureResource|JsonResponse
    {
        $features = $this->featureService->getFeatures($request);
        return Response::paginated(FeatureResource::collection($features), 'Features retrieved successfully.');
    }

    /**
     * Store a newly created Feature resource in storage.
     *
     * @param FeatureRequest $request The validated request.
     * @return FeatureResource|JsonResponse
     */
    public function store(FeatureRequest $request): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->createFeature($request->validated(), $request);
            return Response::created(new FeatureResource($feature->load(['createdBy'])), 'Feature created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create feature: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Feature resource.
     *
     * @param int $id The Feature ID.
     * @return FeatureResource|JsonResponse
     */
    public function show(int $id): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->findFeature($id);
            return Response::success(new FeatureResource($feature), 'Feature retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found.');
        }
    }

    /**
     * Update the specified Feature resource in storage.
     *
     * @param FeatureRequest $request The validated request.
     * @param int $id The Feature ID.
     * @return FeatureResource|JsonResponse
     */
    public function update(FeatureRequest $request, int $id): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->findFeature($id);
            $updatedFeature = $this->featureService->updateFeature($feature, $request->validated(), $request);
            return Response::updated(new FeatureResource($updatedFeature->load(['createdBy', 'updatedBy'])), 'Feature updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update feature: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Feature resource from storage (soft delete).
     *
     * @param int $id The Feature ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $feature = $this->featureService->findFeature($id);
            $this->featureService->deleteFeature($feature);
            return Response::deleted('Feature moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete feature: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Feature resource from trash.
     *
     * @param int $id The Feature ID.
     * @return FeatureResource|JsonResponse
     */
    public function restore(int $id): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->restoreFeature($id);
            return Response::success(new FeatureResource($feature->load(['createdBy', 'updatedBy'])), 'Feature restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore feature: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Feature resource from storage.
     *
     * @param int $id The Feature ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->featureService->forceDeleteFeature($id);
            return Response::deleted('Feature permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete feature: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete features (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:features,id'
        ]);

        try {
            $deletedCount = $this->featureService->bulkDeleteFeatures($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} features moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No features found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete features: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore features from trash.
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
            $restoredCount = $this->featureService->bulkRestoreFeatures($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} features restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No features found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore features: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete features.
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
            $deletedCount = $this->featureService->bulkForceDeleteFeatures($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} features permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No features found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete features: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed features.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->featureService->emptyFeatureTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} features permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of features.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:features,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->featureService->bulkUpdateFeatureStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} features status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No features found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update features status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single feature.
     *
     * @param string $id
     * @return FeatureResource|JsonResponse
     */
    public function toggleStatus(string $id): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->findFeature($id);
            $updatedFeature = $this->featureService->toggleFeatureStatus($feature);
            $statusText = $updatedFeature->status ? 'activated' : 'deactivated';
            return Response::success(new FeatureResource($updatedFeature), "Feature {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle feature status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a feature.
     *
     * @param int $id
     * @return FeatureResource|JsonResponse
     */
    public function duplicate(int $id): FeatureResource|JsonResponse
    {
        try {
            $feature = $this->featureService->findFeature($id);
            $duplicatedFeature = $this->featureService->duplicateFeature($feature);
            return Response::created(new FeatureResource($duplicatedFeature->load(['createdBy'])), 'Feature duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Feature not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate feature: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for features.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->featureService->getFeatureStatistics();
            return Response::success($stats, 'Feature statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve feature statistics: ' . $e->getMessage(), 500);
        }
    }
}
