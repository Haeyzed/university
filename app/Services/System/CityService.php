<?php

namespace App\Services\System;

use App\Models\System\City;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityService
{
    /**
     * Retrieve a paginated list of cities.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getCities(Request $request): LengthAwarePaginator
    {
        return City::query()
            ->with(['country', 'state'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhereHas('country', fn($q) => $q->where('name', "%{$search}%"))
                    ->orWhereHas('state', fn($q) => $q->where('name', "%{$search}%"))
                );
            })
            ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
            ->when($request->filled('state_id'), fn($q) => $q->where('state_id', $request->state_id))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'country_id', 'state_id', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new city.
     *
     * @param array $data
     * @return City
     * @throws Exception
     */
    public function createCity(array $data): City
    {
        return DB::transaction(function () use ($data) {
            $city = new City($data);
            $city->save();

            return $city;
        });
    }

    /**
     * Find a city by ID.
     *
     * @param int $id
     * @return City
     */
    public function findCity(int $id): City
    {
        return City::with(['country', 'state'])->findOrFail($id);
    }

    /**
     * Update an existing city.
     *
     * @param City $city
     * @param array $data
     * @return City
     * @throws Exception
     */
    public function updateCity(City $city, array $data): City
    {
        return DB::transaction(function () use ($city, $data) {
            $city->fill($data);
            $city->save();

            return $city;
        });
    }

    /**
     * Soft delete a city.
     *
     * @param City $city
     * @return bool|null
     * @throws Exception
     */
    public function deleteCity(City $city): ?bool
    {
        return DB::transaction(fn() => $city->delete());
    }

    /**
     * Restore a soft-deleted city.
     *
     * @param int $id
     * @return City
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreCity(int $id): City
    {
        return DB::transaction(function () use ($id) {
            $city = City::onlyTrashed()->findOrFail($id);
            $city->restore();

            return $city;
        });
    }

    /**
     * Permanently delete a city.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteCity(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $city = City::onlyTrashed()->findOrFail($id);
            return $city->forceDelete();
        });
    }

    /**
     * Bulk soft delete cities.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteCities(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = City::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No cities found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore cities.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreCities(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = City::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No cities found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                City::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return City::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete cities.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteCities(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $cities = City::onlyTrashed()->whereIn('id', $ids)->get();
            if ($cities->isEmpty()) {
                throw new ModelNotFoundException('No cities found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($cities as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed cities.
     *
     * @return int
     * @throws Exception
     */
    public function emptyCityTrash(): int
    {
        return DB::transaction(function () {
            $trashedCities = City::onlyTrashed()->get();
            if ($trashedCities->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedCities as $city) {
                $city->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for cities.
     *
     * @return array
     */
    public function getCityStatistics(): array
    {
        return [
            'total' => City::query()->count(),
            'trashed' => City::onlyTrashed()->count(),
            'this_month' => City::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => City::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => City::query()->whereDate('created_at', today())->count(),
        ];
    }
}
