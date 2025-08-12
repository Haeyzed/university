<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\SliderRequest;
use App\Http\Resources\Web\SliderResource;
use App\Services\Web\SliderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Slider
 */
class SliderController extends Controller
{
    protected SliderService $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    /**
     * Display a paginated listing of the Slider resources.
     *
     * @param Request $request
     * @return SliderResource|JsonResponse
     */
    public function index(Request $request): SliderResource|JsonResponse
    {
        $sliders = $this->sliderService->getSliders($request);
        return Response::paginated(SliderResource::collection($sliders), 'Sliders retrieved successfully.');
    }

    /**
     * Store a newly created Slider resource in storage.
     *
     * @param SliderRequest $request The validated request.
     * @return SliderResource|JsonResponse
     */
    public function store(SliderRequest $request): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->createSlider($request->validated(), $request);
            return Response::created(new SliderResource($slider->load(['createdBy'])), 'Slider created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create slider: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Slider resource.
     *
     * @param int $id The Slider ID.
     * @return SliderResource|JsonResponse
     */
    public function show(int $id): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->findSlider($id);
            return Response::success(new SliderResource($slider), 'Slider retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found.');
        }
    }

    /**
     * Update the specified Slider resource in storage.
     *
     * @param SliderRequest $request The validated request.
     * @param int $id The Slider ID.
     * @return SliderResource|JsonResponse
     */
    public function update(SliderRequest $request, int $id): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->findSlider($id);
            $updatedSlider = $this->sliderService->updateSlider($slider, $request->validated(), $request);
            return Response::updated(new SliderResource($updatedSlider->load(['createdBy', 'updatedBy'])), 'Slider updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update slider: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Slider resource from storage (soft delete).
     *
     * @param int $id The Slider ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $slider = $this->sliderService->findSlider($id);
            $this->sliderService->deleteSlider($slider);
            return Response::deleted('Slider moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete slider: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Slider resource from trash.
     *
     * @param int $id The Slider ID.
     * @return SliderResource|JsonResponse
     */
    public function restore(int $id): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->restoreSlider($id);
            return Response::success(new SliderResource($slider->load(['createdBy', 'updatedBy'])), 'Slider restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore slider: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Slider resource from storage.
     *
     * @param int $id The Slider ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->sliderService->forceDeleteSlider($id);
            return Response::deleted('Slider permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete slider: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete sliders (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:sliders,id'
        ]);

        try {
            $deletedCount = $this->sliderService->bulkDeleteSliders($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} sliders moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No sliders found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete sliders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore sliders from trash.
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
            $restoredCount = $this->sliderService->bulkRestoreSliders($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} sliders restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No sliders found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore sliders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete sliders.
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
            $deletedCount = $this->sliderService->bulkForceDeleteSliders($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} sliders permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No sliders found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete sliders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed sliders.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->sliderService->emptySliderTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} sliders permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of sliders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:sliders,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->sliderService->bulkUpdateSliderStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} sliders status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No sliders found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update sliders status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single slider.
     *
     * @param string $id
     * @return SliderResource|JsonResponse
     */
    public function toggleStatus(string $id): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->findSlider($id);
            $updatedSlider = $this->sliderService->toggleSliderStatus($slider);
            $statusText = $updatedSlider->status ? 'activated' : 'deactivated';
            return Response::success(new SliderResource($updatedSlider), "Slider {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle slider status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a slider.
     *
     * @param int $id
     * @return SliderResource|JsonResponse
     */
    public function duplicate(int $id): SliderResource|JsonResponse
    {
        try {
            $slider = $this->sliderService->findSlider($id);
            $duplicatedSlider = $this->sliderService->duplicateSlider($slider);
            return Response::created(new SliderResource($duplicatedSlider->load(['createdBy'])), 'Slider duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Slider not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate slider: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for sliders.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->sliderService->getSliderStatistics();
            return Response::success($stats, 'Slider statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve slider statistics: ' . $e->getMessage(), 500);
        }
    }
}
