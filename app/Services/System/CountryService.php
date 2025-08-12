<?php

namespace App\Services\System;

use App\Models\System\Country;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryService
{
    /**
     * Retrieve a paginated list of countries.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getCountries(Request $request): LengthAwarePaginator
    {
        return Country::query()
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhereLike('iso2', "%{$search}%")
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', (bool) $request->status))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'iso2', 'status', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new country.
     *
     * @param array $data
     * @return Country
     * @throws Exception
     */
    public function createCountry(array $data): Country
    {
        return DB::transaction(function () use ($data) {
            $country = new Country($data);
            $country->status = $data['status'] ?? true;

            if (auth()->check()) {
                $country->created_by = auth()->id();
            }
            $country->save();

            return $country;
        });
    }

    /**
     * Find a country by ID.
     *
     * @param int $id
     * @return Country
     */
    public function findCountry(int $id): Country
    {
        return Country::with(['createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing country.
     *
     * @param Country $country
     * @param array $data
     * @return Country
     * @throws Exception
     */
    public function updateCountry(Country $country, array $data): Country
    {
        return DB::transaction(function () use ($country, $data) {
            $country->fill($data);

            if (auth()->check()) {
                $country->updated_by = auth()->id();
            }
            $country->save();

            return $country;
        });
    }

    /**
     * Soft delete a country.
     *
     * @param Country $country
     * @return bool|null
     * @throws Exception
     */
    public function deleteCountry(Country $country): ?bool
    {
        return DB::transaction(fn() => $country->delete());
    }

    /**
     * Restore a soft-deleted country.
     *
     * @param int $id
     * @return Country
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreCountry(int $id): Country
    {
        return DB::transaction(function () use ($id) {
            $country = Country::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $country->updated_by = auth()->id();
            }
            $country->restore();

            return $country;
        });
    }

    /**
     * Permanently delete a country.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteCountry(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $country = Country::onlyTrashed()->findOrFail($id);
            return $country->forceDelete();
        });
    }

    /**
     * Bulk soft delete countries.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteCountries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Country::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No countries found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore countries.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreCountries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Country::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No countries found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Country::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Country::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete countries.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteCountries(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $countries = Country::onlyTrashed()->whereIn('id', $ids)->get();
            if ($countries->isEmpty()) {
                throw new ModelNotFoundException('No countries found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($countries as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed countries.
     *
     * @return int
     * @throws Exception
     */
    public function emptyCountryTrash(): int
    {
        return DB::transaction(function () {
            $trashedCountries = Country::onlyTrashed()->get();
            if ($trashedCountries->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedCountries as $country) {
                $country->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk update status of countries.
     *
     * @param array $ids
     * @param bool $status
     * @return int
     * @throws Exception
     */
    public function bulkUpdateCountryStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            $updatedCount = Country::query()->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);
            if ($updatedCount === 0) {
                throw new ModelNotFoundException('No countries found with the provided IDs.');
            }
            return $updatedCount;
        });
    }

    /**
     * Toggle status of a single country.
     *
     * @param Country $country
     * @return Country
     * @throws Exception
     */
    public function toggleCountryStatus(Country $country): Country
    {
        return DB::transaction(function () use ($country) {
            $country->status = !$country->status;
            if (auth()->check()) {
                $country->updated_by = auth()->id();
            }
            $country->save();
            return $country;
        });
    }

    /**
     * Duplicate a country.
     *
     * @param Country $country
     * @return Country
     * @throws Exception
     */
    public function duplicateCountry(Country $country): Country
    {
        return DB::transaction(function () use ($country) {
            $duplicatedCountry = $country->replicate();
            $duplicatedCountry->name = $country->name . ' (Copy)';
            $duplicatedCountry->iso2 = $country->iso2 . '_copy_' . uniqid(); // Ensure unique ISO2
            $duplicatedCountry->status = false;

            if (auth()->check()) {
                $duplicatedCountry->created_by = auth()->id();
                $duplicatedCountry->updated_by = null;
            }
            $duplicatedCountry->save();
            return $duplicatedCountry;
        });
    }

    /**
     * Get statistics for countries.
     *
     * @return array
     */
    public function getCountryStatistics(): array
    {
        return [
            'total' => Country::query()->count(),
            'active' => Country::query()->where('status', true)->count(),
            'inactive' => Country::query()->where('status', false)->count(),
            'trashed' => Country::onlyTrashed()->count(),
            'this_month' => Country::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Country::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Country::query()->whereDate('created_at', today())->count(),
        ];
    }
}
