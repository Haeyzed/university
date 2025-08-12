<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\CountryRequest;
use App\Http\Resources\System\CountryResource;
use App\Services\System\CountryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Country
 */
class CountryController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Display a paginated listing of the Country resources.
     *
     * @param Request $request
     * @return CountryResource|JsonResponse
     */
    public function index(Request $request): CountryResource|JsonResponse
    {
        $countries = $this->countryService->getCountries($request);
        return Response::paginated(CountryResource::collection($countries), 'Countries retrieved successfully.');
    }

    /**
     * Store a newly created Country resource in storage.
     *
     * @param CountryRequest $request The validated request.
     * @return CountryResource|JsonResponse
     */
    public function store(CountryRequest $request): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->createCountry($request->validated());
            return Response::created(new CountryResource($country), 'Country created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create country: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Country resource.
     *
     * @param int $id The Country ID.
     * @return CountryResource|JsonResponse
     */
    public function show(int $id): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->findCountry($id);
            return Response::success(new CountryResource($country), 'Country retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found.');
        }
    }

    /**
     * Update the specified Country resource in storage.
     *
     * @param CountryRequest $request The validated request.
     * @param int $id The Country ID.
     * @return CountryResource|JsonResponse
     */
    public function update(CountryRequest $request, int $id): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->findCountry($id);
            $updatedCountry = $this->countryService->updateCountry($country, $request->validated());
            return Response::updated(new CountryResource($updatedCountry), 'Country updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update country: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Country resource from storage (soft delete).
     *
     * @param int $id The Country ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $country = $this->countryService->findCountry($id);
            $this->countryService->deleteCountry($country);
            return Response::deleted('Country moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete country: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Country resource from trash.
     *
     * @param int $id The Country ID.
     * @return CountryResource|JsonResponse
     */
    public function restore(int $id): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->restoreCountry($id);
            return Response::success(new CountryResource($country), 'Country restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore country: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Country resource from storage.
     *
     * @param int $id The Country ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->countryService->forceDeleteCountry($id);
            return Response::deleted('Country permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete country: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete countries (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:countries,id'
        ]);

        try {
            $deletedCount = $this->countryService->bulkDeleteCountries($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} countries moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No countries found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete countries: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore countries from trash.
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
            $restoredCount = $this->countryService->bulkRestoreCountries($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} countries restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No countries found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore countries: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete countries.
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
            $deletedCount = $this->countryService->bulkForceDeleteCountries($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} countries permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No countries found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete countries: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed countries.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->countryService->emptyCountryTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} countries permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update status of countries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:countries,id',
            'status' => 'required|boolean'
        ]);

        try {
            $updatedCount = $this->countryService->bulkUpdateCountryStatus($request->input('ids'), $request->boolean('status'));
            return Response::success(['updated_count' => $updatedCount], "{$updatedCount} countries status updated successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No countries found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to update countries status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle status of a single country.
     *
     * @param string $id
     * @return CountryResource|JsonResponse
     */
    public function toggleStatus(string $id): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->findCountry($id);
            $updatedCountry = $this->countryService->toggleCountryStatus($country);
            $statusText = $updatedCountry->status ? 'activated' : 'deactivated';
            return Response::success(new CountryResource($updatedCountry), "Country {$statusText} successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to toggle country status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Duplicate a country.
     *
     * @param int $id
     * @return CountryResource|JsonResponse
     */
    public function duplicate(int $id): CountryResource|JsonResponse
    {
        try {
            $country = $this->countryService->findCountry($id);
            $duplicatedCountry = $this->countryService->duplicateCountry($country);
            return Response::created(new CountryResource($duplicatedCountry->load(['createdBy'])), 'Country duplicated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Country not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to duplicate country: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for countries.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->countryService->getCountryStatistics();
            return Response::success($stats, 'Country statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve country statistics: ' . $e->getMessage(), 500);
        }
    }
}
