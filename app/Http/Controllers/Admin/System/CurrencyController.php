<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\CurrencyRequest;
use App\Http\Resources\System\CurrencyResource;
use App\Services\System\CurrencyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Currency
 */
class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a paginated listing of the Currency resources.
     *
     * @param Request $request
     * @return CurrencyResource|JsonResponse
     */
    public function index(Request $request): CurrencyResource|JsonResponse
    {
        $currencies = $this->currencyService->getCurrencies($request);
        return Response::paginated(CurrencyResource::collection($currencies), 'Currencies retrieved successfully.');
    }

    /**
     * Store a newly created Currency resource in storage.
     *
     * @param CurrencyRequest $request The validated request.
     * @return CurrencyResource|JsonResponse
     */
    public function store(CurrencyRequest $request): CurrencyResource|JsonResponse
    {
        try {
            $currency = $this->currencyService->createCurrency($request->validated());
            return Response::created(new CurrencyResource($currency->load(['createdBy'])), 'Currency created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create currency: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Currency resource.
     *
     * @param int $id The Currency ID.
     * @return CurrencyResource|JsonResponse
     */
    public function show(int $id): CurrencyResource|JsonResponse
    {
        try {
            $currency = $this->currencyService->findCurrency($id);
            return Response::success(new CurrencyResource($currency), 'Currency retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Currency not found.');
        }
    }

    /**
     * Update the specified Currency resource in storage.
     *
     * @param CurrencyRequest $request The validated request.
     * @param int $id The Currency ID.
     * @return CurrencyResource|JsonResponse
     */
    public function update(CurrencyRequest $request, int $id): CurrencyResource|JsonResponse
    {
        try {
            $currency = $this->currencyService->findCurrency($id);
            $updatedCurrency = $this->currencyService->updateCurrency($currency, $request->validated());
            return Response::updated(new CurrencyResource($updatedCurrency->load(['createdBy', 'updatedBy'])), 'Currency updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Currency not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update currency: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Currency resource from storage (soft delete).
     *
     * @param int $id The Currency ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $currency = $this->currencyService->findCurrency($id);
            $this->currencyService->deleteCurrency($currency);
            return Response::deleted('Currency moved to trash successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Currency not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete currency: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified Currency resource from trash.
     *
     * @param int $id The Currency ID.
     * @return CurrencyResource|JsonResponse
     */
    public function restore(int $id): CurrencyResource|JsonResponse
    {
        try {
            $currency = $this->currencyService->restoreCurrency($id);
            return Response::success(new CurrencyResource($currency->load(['createdBy', 'updatedBy'])), 'Currency restored successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Currency not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore currency: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Permanently delete the specified Currency resource from storage.
     *
     * @param int $id The Currency ID.
     * @return JsonResponse
     */
    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->currencyService->forceDeleteCurrency($id);
            return Response::deleted('Currency permanently deleted.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Currency not found in trash.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete currency: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete currencies (soft delete).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:currencies,id'
        ]);

        try {
            $deletedCount = $this->currencyService->bulkDeleteCurrencies($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} currencies moved to trash successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No currencies found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete currencies: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk restore currencies from trash.
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
            $restoredCount = $this->currencyService->bulkRestoreCurrencies($request->input('ids'));
            return Response::success(['restored_count' => $restoredCount], "{$restoredCount} currencies restored successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No currencies found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to restore currencies: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk permanently delete currencies.
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
            $deletedCount = $this->currencyService->bulkForceDeleteCurrencies($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} currencies permanently deleted.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No currencies found in trash with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to permanently delete currencies: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Empty trash - permanently delete all trashed currencies.
     *
     * @return JsonResponse
     */
    public function emptyTrash(): JsonResponse
    {
        try {
            $deletedCount = $this->currencyService->emptyCurrencyTrash();
            $message = $deletedCount === 0 ? 'Trash is already empty.' : "Trash emptied. {$deletedCount} currencies permanently deleted.";
            return Response::success(['deleted_count' => $deletedCount], $message);
        } catch (Throwable $e) {
            return Response::error('Failed to empty trash: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for currencies.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->currencyService->getCurrencyStatistics();
            return Response::success($stats, 'Currency statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve currency statistics: ' . $e->getMessage(), 500);
        }
    }
}
