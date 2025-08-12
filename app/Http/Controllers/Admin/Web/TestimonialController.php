<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\TestimonialRequest;
use App\Http\Resources\Web\TestimonialResource;
use App\Services\Web\TestimonialService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Testimonial
 */
class TestimonialController extends Controller
{
    protected TestimonialService $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Display a paginated listing of the Testimonial resources.
     *
     * @param Request $request
     * @return TestimonialResource|JsonResponse
     */
    public function index(Request $request): TestimonialResource|JsonResponse
    {
        $testimonials = $this->testimonialService->getTestimonials($request);
        return Response::paginated(TestimonialResource::collection($testimonials), 'Testimonials retrieved successfully.');
    }

    /**
     * Store a newly created Testimonial resource in storage.
     *
     * @param TestimonialRequest $request The validated request.
     * @return TestimonialResource|JsonResponse
     */
    public function store(TestimonialRequest $request): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->createTestimonial($request->validated(), $request);
            return Response::created(new TestimonialResource($testimonial->load(['createdBy'])), 'Testimonial created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create testimonial: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Testimonial resource.
     *
     * @param int $id The Testimonial ID.
     * @return TestimonialResource|JsonResponse
     */
    public function show(int $id): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->findTestimonial($id);
            return Response::success(new TestimonialResource($testimonial), 'Testimonial retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found.');
        }
    }

    /**
     * Update the specified Testimonial resource in storage.
     *
     * @param TestimonialRequest $request The validated request.
     * @param int $id The Testimonial ID.
     * @return TestimonialResource|JsonResponse
     */
    public function update(TestimonialRequest $request, int $id): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->findTestimonial($id);
            $updatedTestimonial = $this->testimonialService->updateTestimonial($testimonial, $request->validated(), $request);
            return Response::updated(new TestimonialResource($updatedTestimonial->load(['createdBy', 'updatedBy'])), 'Testimonial updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update testimonial: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Testimonial resource from storage (soft delete).
     *
     * @param int $id The Testimonial ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->findTestimonial($id);
            $this->testimonialService->deleteTestimonial($testimonial);
            return Response::deleted('Testimonial moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete testimonial: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Testimonial resource from trash.
     *
     * @param int $id The Testimonial ID.
     * @return TestimonialResource|JsonResponse
     */
    public function restore(int $id): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->restoreTestimonial($id);
            return Response::success(new TestimonialResource($testimonial->load(['createdBy', 'updatedBy'])), 'Testimonial restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore testimonial: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Testimonial resource from storage.
     *
     * @param int $id The Testimonial ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->testimonialService->forceDeleteTestimonial($id);
            return Response::deleted('Testimonial permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete testimonial: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete testimonials (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:testimonials,id'
        ]);

        try {
            $deletedCount = $this->testimonialService->bulkDeleteTestimonials($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} testimonials moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No testimonials found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete testimonials: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore testimonials from trash.
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
            $restoredCount = $this->testimonialService->bulkRestoreTestimonials($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} testimonials restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No testimonials found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore testimonials: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete testimonials.
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
            $deletedCount = $this->testimonialService->bulkForceDeleteTestimonials($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} testimonials permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No testimonials found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete testimonials: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed testimonials.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->testimonialService->emptyTestimonialTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} testimonials permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of testimonials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:testimonials,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->testimonialService->bulkUpdateTestimonialStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} testimonials status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No testimonials found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update testimonials status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single testimonial.
     *
     * @param string $id
     * @return TestimonialResource|JsonResponse
     */
    public function toggleStatus(string $id): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->findTestimonial($id);
            $updatedTestimonial = $this->testimonialService->toggleTestimonialStatus($testimonial);
            $statusText = $updatedTestimonial->status ? 'activated' : 'deactivated';
            return Response::success(new TestimonialResource($updatedTestimonial), "Testimonial {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle testimonial status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a testimonial.
     *
     * @param int $id
     * @return TestimonialResource|JsonResponse
     */
    public function duplicate(int $id): TestimonialResource|JsonResponse
    {
        try {
            $testimonial = $this->testimonialService->findTestimonial($id);
            $duplicatedTestimonial = $this->testimonialService->duplicateTestimonial($testimonial);
            return Response::created(new TestimonialResource($duplicatedTestimonial->load(['createdBy'])), 'Testimonial duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Testimonial not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate testimonial: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for testimonials.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->testimonialService->getTestimonialStatistics();
            return Response::success($stats, 'Testimonial statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve testimonial statistics: ' . $e->getMessage(), 500);
        }
    }
}
