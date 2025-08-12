<?php

namespace App\Services\System;

use App\Models\System\Currency;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyService
{
    /**
     * Retrieve a paginated list of currencies.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getCurrencies(Request $request): LengthAwarePaginator
    {
        return Currency::query()
            ->with(['country', 'createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhere('code', "%{$search}%")
                    ->orWhere('symbol', "%{$search}%")
                    ->orWhereHas('country', fn($q) => $q->where('name', "%{$search}%"))
                );
            })
            ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'code', 'country_id', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new currency.
     *
     * @param array $data
     * @return Currency
     * @throws Exception
     */
    public function createCurrency(array $data): Currency
    {
        return DB::transaction(function () use ($data) {
            $currency = new Currency($data);

            if (auth()->check()) {
                $currency->created_by = auth()->id();
            }
            $currency->save();

            return $currency;
        });
    }

    /**
     * Find a currency by ID.
     *
     * @param int $id
     * @return Currency
     */
    public function findCurrency(int $id): Currency
    {
        return Currency::with(['country', 'createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing currency.
     *
     * @param Currency $currency
     * @param array $data
     * @return Currency
     * @throws Exception
     */
    public function updateCurrency(Currency $currency, array $data): Currency
    {
        return DB::transaction(function () use ($currency, $data) {
            $currency->fill($data);

            if (auth()->check()) {
                $currency->updated_by = auth()->id();
            }
            $currency->save();

            return $currency;
        });
    }

    /**
     * Soft delete a currency.
     *
     * @param Currency $currency
     * @return bool|null
     * @throws Exception
     */
    public function deleteCurrency(Currency $currency): ?bool
    {
        return DB::transaction(fn() => $currency->delete());
    }

    /**
     * Restore a soft-deleted currency.
     *
     * @param int $id
     * @return Currency
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreCurrency(int $id): Currency
    {
        return DB::transaction(function () use ($id) {
            $currency = Currency::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $currency->updated_by = auth()->id();
            }
            $currency->restore();

            return $currency;
        });
    }

    /**
     * Permanently delete a currency.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteCurrency(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $currency = Currency::onlyTrashed()->findOrFail($id);
            return $currency->forceDelete();
        });
    }

    /**
     * Bulk soft delete currencies.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteCurrencies(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Currency::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No currencies found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore currencies.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreCurrencies(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Currency::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No currencies found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Currency::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Currency::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete currencies.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteCurrencies(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $currencies = Currency::onlyTrashed()->whereIn('id', $ids)->get();
            if ($currencies->isEmpty()) {
                throw new ModelNotFoundException('No currencies found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($currencies as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed currencies.
     *
     * @return int
     * @throws Exception
     */
    public function emptyCurrencyTrash(): int
    {
        return DB::transaction(function () {
            $trashedCurrencies = Currency::onlyTrashed()->get();
            if ($trashedCurrencies->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedCurrencies as $currency) {
                $currency->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for currencies.
     *
     * @return array
     */
    public function getCurrencyStatistics(): array
    {
        return [
            'total' => Currency::query()->count(),
            'trashed' => Currency::onlyTrashed()->count(),
            'this_month' => Currency::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Currency::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Currency::query()->whereDate('created_at', today())->count(),
        ];
    }
}
