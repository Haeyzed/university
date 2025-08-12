<?php

namespace App\Services\System;

use App\Models\System\Timezone;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimezoneService
{
    /**
     * Retrieve a paginated list of timezones.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getTimezones(Request $request): LengthAwarePaginator
    {
        return Timezone::query()
            ->with(['country', 'createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhereHas('country', fn($q) => $q->where('name', "%{$search}%"))
                );
            })
            ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'country_id', 'created_at', 'updated_at', 'deleted_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new timezone.
     *
     * @param array $data
     * @return Timezone
     * @throws Exception
     */
    public function createTimezone(array $data): Timezone
    {
        return DB::transaction(function () use ($data) {
            $timezone = new Timezone($data);

            if (auth()->check()) {
                $timezone->created_by = auth()->id();
            }
            $timezone->save();

            return $timezone;
        });
    }

    /**
     * Find a timezone by ID.
     *
     * @param int $id
     * @return Timezone
     */
    public function findTimezone(int $id): Timezone
    {
        return Timezone::with(['country', 'createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing timezone.
     *
     * @param Timezone $timezone
     * @param array $data
     * @return Timezone
     * @throws Exception
     */
    public function updateTimezone(Timezone $timezone, array $data): Timezone
    {
        return DB::transaction(function () use ($timezone, $data) {
            $timezone->fill($data);

            if (auth()->check()) {
                $timezone->updated_by = auth()->id();
            }
            $timezone->save();

            return $timezone;
        });
    }

    /**
     * Soft delete a timezone.
     *
     * @param Timezone $timezone
     * @return bool|null
     * @throws Exception
     */
    public function deleteTimezone(Timezone $timezone): ?bool
    {
        return DB::transaction(fn() => $timezone->delete());
    }

    /**
     * Restore a soft-deleted timezone.
     *
     * @param int $id
     * @return Timezone
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreTimezone(int $id): Timezone
    {
        return DB::transaction(function () use ($id) {
            $timezone = Timezone::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $timezone->updated_by = auth()->id();
            }
            $timezone->restore();

            return $timezone;
        });
    }

    /**
     * Permanently delete a timezone.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteTimezone(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $timezone = Timezone::onlyTrashed()->findOrFail($id);
            return $timezone->forceDelete();
        });
    }

    /**
     * Bulk soft delete timezones.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteTimezones(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = Timezone::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No timezones found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore timezones.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreTimezones(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = Timezone::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No timezones found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                Timezone::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return Timezone::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete timezones.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteTimezones(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $timezones = Timezone::onlyTrashed()->whereIn('id', $ids)->get();
            if ($timezones->isEmpty()) {
                throw new ModelNotFoundException('No timezones found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($timezones as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed timezones.
     *
     * @return int
     * @throws Exception
     */
    public function emptyTimezoneTrash(): int
    {
        return DB::transaction(function () {
            $trashedTimezones = Timezone::onlyTrashed()->get();
            if ($trashedTimezones->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedTimezones as $timezone) {
                $timezone->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for timezones.
     *
     * @return array
     */
    public function getTimezoneStatistics(): array
    {
        return [
            'total' => Timezone::query()->count(),
            'trashed' => Timezone::onlyTrashed()->count(),
            'this_month' => Timezone::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Timezone::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Timezone::query()->whereDate('created_at', today())->count(),
        ];
    }
}
