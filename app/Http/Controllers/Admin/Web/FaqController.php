<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\FaqRequest;
use App\Http\Resources\Web\FaqResource;
use App\Services\Web\FaqService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Faq
 */
class FaqController extends Controller
{
    protected FaqService $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    /**
     * Display a paginated listing of the Faq resources.
     *
     * @param Request $request
     * @return FaqResource|JsonResponse
     */
    public function index(Request $request): FaqResource|JsonResponse
    {
        $faqs = $this->faqService->getFaqs($request);
        return Response::paginated(FaqResource::collection($faqs), 'FAQs retrieved successfully.');
    }

    /**
     * Store a newly created Faq resource in storage.
     *
     * @param FaqRequest $request The validated request.
     * @return FaqResource|JsonResponse
     */
    public function store(FaqRequest $request): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->createFaq($request->validated());
            return Response::created(new FaqResource($faq->load(['createdBy'])), 'FAQ created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create FAQ: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Faq resource.
     *
     * @param int $id The Faq ID.
     * @return FaqResource|JsonResponse
     */
    public function show(int $id): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->findFaq($id);
            return Response::success(new FaqResource($faq), 'FAQ retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found.');
        }
    }

    /**
     * Update the specified Faq resource in storage.
     *
     * @param FaqRequest $request The validated request.
     * @param int $id The Faq ID.
     * @return FaqResource|JsonResponse
     */
    public function update(FaqRequest $request, int $id): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->findFaq($id);
            $updatedFaq = $this->faqService->updateFaq($faq, $request->validated());
            return Response::updated(new FaqResource($updatedFaq->load(['createdBy', 'updatedBy'])), 'FAQ updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Faq resource from storage (soft delete).
     *
     * @param int $id The Faq ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $faq = $this->faqService->findFaq($id);
            $this->faqService->deleteFaq($faq);
            return Response::deleted('FAQ moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Faq resource from trash.
     *
     * @param int $id The Faq ID.
     * @return FaqResource|JsonResponse
     */
    public function restore(int $id): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->restoreFaq($id);
            return Response::success(new FaqResource($faq->load(['createdBy', 'updatedBy'])), 'FAQ restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Faq resource from storage.
     *
     * @param int $id The Faq ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->faqService->forceDeleteFaq($id);
            return Response::deleted('FAQ permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete FAQs (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:faqs,id'
        ]);

        try {
            $deletedCount = $this->faqService->bulkDeleteFaqs($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} FAQs moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No FAQs found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete FAQs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore FAQs from trash.
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
            $restoredCount = $this->faqService->bulkRestoreFaqs($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} FAQs restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No FAQs found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore FAQs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete FAQs.
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
            $deletedCount = $this->faqService->bulkForceDeleteFaqs($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} FAQs permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No FAQs found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete FAQs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed FAQs.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->faqService->emptyFaqTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} FAQs permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of FAQs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:faqs,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->faqService->bulkUpdateFaqStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} FAQs status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No FAQs found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update FAQs status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single FAQ.
     *
     * @param string $id
     * @return FaqResource|JsonResponse
     */
    public function toggleStatus(string $id): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->findFaq($id);
            $updatedFaq = $this->faqService->toggleFaqStatus($faq);
            $statusText = $updatedFaq->status ? 'activated' : 'deactivated';
            return Response::success(new FaqResource($updatedFaq), "FAQ {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle FAQ status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a FAQ.
     *
     * @param int $id
     * @return FaqResource|JsonResponse
     */
    public function duplicate(int $id): FaqResource|JsonResponse
    {
        try {
            $faq = $this->faqService->findFaq($id);
            $duplicatedFaq = $this->faqService->duplicateFaq($faq);
            return Response::created(new FaqResource($duplicatedFaq->load(['createdBy'])), 'FAQ duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('FAQ not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for FAQs.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->faqService->getFaqStatistics();
            return Response::success($stats, 'FAQ statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve FAQ statistics: ' . $e->getMessage(), 500);
        }
    }
}
