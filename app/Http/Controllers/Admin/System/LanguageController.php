<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\LanguageRequest;
use App\Http\Resources\System\LanguageResource;
use App\Services\System\LanguageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Language
 */
class LanguageController extends Controller
{
    protected LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Display a paginated listing of the Language resources.
     *
     * @param Request $request
     * @return LanguageResource|JsonResponse
     */
    public function index(Request $request): LanguageResource|JsonResponse
    {
        $languages = $this->languageService->getLanguages($request);
        return Response::paginated(LanguageResource::collection($languages), 'Languages retrieved successfully.');
    }

    /**
     * Store a newly created Language resource in storage.
     *
     * @param LanguageRequest $request The validated request.
     * @return LanguageResource|JsonResponse
     */
    public function store(LanguageRequest $request): LanguageResource|JsonResponse
    {
        try {
            $language = $this->languageService->createLanguage($request->validated());
            return Response::created(new LanguageResource($language->load(['createdBy'])), 'Language created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create language: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Language resource.
     *
     * @param int $id The Language ID.
     * @return LanguageResource|JsonResponse
     */
    public function show(int $id): LanguageResource|JsonResponse
    {
        try {
            $language = $this->languageService->findLanguage($id);
            return Response::success(new LanguageResource($language), 'Language retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Language not found.');
        }
    }

    /**
     * Update the specified Language resource in storage.
     *
     * @param LanguageRequest $request The validated request.
     * @param int $id The Language ID.
     * @return LanguageResource|JsonResponse
     */
    public function update(LanguageRequest $request, int $id): LanguageResource|JsonResponse
    {
        try {
            $language = $this->languageService->findLanguage($id);
            $updatedLanguage = $this->languageService->updateLanguage($language, $request->validated());
            return Response::updated(new LanguageResource($updatedLanguage->load(['createdBy', 'updatedBy'])), 'Language updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Language not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update language: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Language resource from storage (soft delete).
     *
     * @param int $id The Language ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $language = $this->languageService->findLanguage($id);
            $this->languageService->deleteLanguage($language);
            return Response::deleted('Language moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Language not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete language: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Language resource from trash.
     *
     * @param int $id The Language ID.
     * @return LanguageResource|JsonResponse
     */
    public function restore(int $id): LanguageResource|JsonResponse
    {
        try {
            $language = $this->languageService->restoreLanguage($id);
            return Response::success(new LanguageResource($language->load(['createdBy', 'updatedBy'])), 'Language restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Language not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore language: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Language resource from storage.
     *
     * @param int $id The Language ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->languageService->forceDeleteLanguage($id);
            return Response::deleted('Language permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Language not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete language: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete languages (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:languages,id'
        ]);

        try {
            $deletedCount = $this->languageService->bulkDeleteLanguages($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} languages moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No languages found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete languages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore languages from trash.
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
            $restoredCount = $this->languageService->bulkRestoreLanguages($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} languages restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No languages found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore languages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete languages.
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
            $deletedCount = $this->languageService->bulkForceDeleteLanguages($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} languages permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No languages found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete languages: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed languages.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->languageService->emptyLanguageTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} languages permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for languages.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->languageService->getLanguageStatistics();
            return Response::success($stats, 'Language statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve language statistics: ' . $e->getMessage(), 500);
        }
    }
}
