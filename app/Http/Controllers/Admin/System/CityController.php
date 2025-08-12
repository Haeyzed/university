<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\CityRequest;
use App\Http\Resources\System\CityResource;
use App\Services\System\CityService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags City
 */
class CityController extends Controller
{
    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * Display a paginated listing of the City resources.
     *
     * @param Request $request
     * @return CityResource|JsonResponse
     */
    public function index(Request $request): CityResource|JsonResponse
    {
        $cities = $this->cityService->getCities($request);
        return Response::paginated(CityResource::collection($cities), 'Cities retrieved successfully.');
    }

    /**
     * Store a newly created City resource in storage.
     *
     * @param CityRequest $request The validated request.
     * @return CityResource|JsonResponse
     */
    public function store(CityRequest $request): CityResource|JsonResponse
    {
        try {
            $city = $this->cityService->createCity($request->validated());
            return Response::created(new CityResource($city), 'City created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create city: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified City resource.
     *
     * @param int $id The City ID.
     * @return CityResource|JsonResponse
     */
    public function show(int $id): CityResource|JsonResponse
    {
        try {
            $city = $this->cityService->findCity($id);
            return Response::success(new CityResource($city), 'City retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('City not found.');
        }
    }

    /**
     * Update the specified City resource in storage.
     *
     * @param CityRequest $request The validated request.
     * @param int $id The City ID.
     * @return CityResource|JsonResponse
     */
    public function update(CityRequest $request, int $id): CityResource|JsonResponse
    {
        try {
            $city = $this->cityService->findCity($id);
            $updatedCity = $this->cityService->updateCity($city, $request->validated());
            return Response::updated(new CityResource($updatedCity), 'City updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('City not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update city: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified City resource from storage (soft delete).
     *
     * @param int $id The City ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $city = $this->cityService->findCity($id);
            $this->cityService->deleteCity($city);
            return Response::deleted('City moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('City not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete city: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified City resource from trash.
     *
     * @param int $id The City ID.
     * @return CityResource|JsonResponse
     */
    public function restore(int $id): CityResource|JsonResponse
    {
        try {
            $city = $this->cityService->restoreCity($id);
            return Response::success(new CityResource($city), 'City restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('City not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore city: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified City resource from storage.
     *
     * @param int $id The City ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->cityService->forceDeleteCity($id);
            return Response::deleted('City permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('City not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete city: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete cities (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:cities,id'
        ]);

        try {
            $deletedCount = $this->cityService->bulkDeleteCities($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} cities moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No cities found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete cities: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore cities from trash.
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
            $restoredCount = $this->cityService->bulkRestoreCities($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} cities restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No cities found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore cities: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete cities.
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
            $deletedCount = $this->cityService->bulkForceDeleteCities($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} cities permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No cities found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete cities: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed cities.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->cityService->emptyCityTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} cities permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for cities.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->cityService->getCityStatistics();
            return Response::success($stats, 'City statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve city statistics: ' . $e->getMessage(), 500);
        }
    }
}
