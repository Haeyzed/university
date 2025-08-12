<?php

namespace App\Services\System;

use App\Models\System\State;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateService
{
    /**
     * Retrieve a paginated list of states.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getStates(Request $request): LengthAwarePaginator
    {
        return State::query()
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
     * Create a new state.
     *
     * @param array $data
     * @return State
     * @throws Exception
     */
    public function createState(array $data): State
    {
        return DB::transaction(function () use ($data) {
            $state = new State($data);

            if (auth()->check()) {
                $state->created_by = auth()->id();
            }
            $state->save();

            return $state;
        });
    }

    /**
     * Find a state by ID.
     *
     * @param int $id
     * @return State
     */
    public function findState(int $id): State
    {
        return State::with(['country', 'createdBy', 'updatedBy'])->findOrFail($id);
    }

    /**
     * Update an existing state.
     *
     * @param State $state
     * @param array $data
     * @return State
     * @throws Exception
     */
    public function updateState(State $state, array $data): State
    {
        return DB::transaction(function () use ($state, $data) {
            $state->fill($data);

            if (auth()->check()) {
                $state->updated_by = auth()->id();
            }
            $state->save();

            return $state;
        });
    }

    /**
     * Soft delete a state.
     *
     * @param State $state
     * @return bool|null
     * @throws Exception
     */
    public function deleteState(State $state): ?bool
    {
        return DB::transaction(fn() => $state->delete());
    }

    /**
     * Restore a soft-deleted state.
     *
     * @param int $id
     * @return State
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function restoreState(int $id): State
    {
        return DB::transaction(function () use ($id) {
            $state = State::onlyTrashed()->findOrFail($id);

            if (auth()->check()) {
                $state->updated_by = auth()->id();
            }
            $state->restore();

            return $state;
        });
    }

    /**
     * Permanently delete a state.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function forceDeleteState(int $id): ?bool
    {
        return DB::transaction(function () use ($id) {
            $state = State::onlyTrashed()->findOrFail($id);
            return $state->forceDelete();
        });
    }

    /**
     * Bulk soft delete states.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteStates(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = State::query()->whereIn('id', $ids)->delete();
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No states found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Bulk restore states.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkRestoreStates(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $existingIds = State::onlyTrashed()->whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                throw new ModelNotFoundException('No states found in trash with the provided IDs.');
            }

            if (auth()->check()) {
                State::onlyTrashed()->whereIn('id', $existingIds)->update(['updated_by' => auth()->id()]);
            }
            return State::onlyTrashed()->whereIn('id', $existingIds)->restore();
        });
    }

    /**
     * Bulk permanently delete states.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkForceDeleteStates(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $states = State::onlyTrashed()->whereIn('id', $ids)->get();
            if ($states->isEmpty()) {
                throw new ModelNotFoundException('No states found in trash with the provided IDs.');
            }

            $deletedCount = 0;
            foreach ($states as $item) {
                $item->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Empty trash - permanently delete all trashed states.
     *
     * @return int
     * @throws Exception
     */
    public function emptyStateTrash(): int
    {
        return DB::transaction(function () {
            $trashedStates = State::onlyTrashed()->get();
            if ($trashedStates->isEmpty()) {
                return 0;
            }

            $deletedCount = 0;
            foreach ($trashedStates as $state) {
                $state->forceDelete();
                $deletedCount++;
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for states.
     *
     * @return array
     */
    public function getStateStatistics(): array
    {
        return [
            'total' => State::query()->count(),
            'trashed' => State::onlyTrashed()->count(),
            'this_month' => State::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => State::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => State::query()->whereDate('created_at', today())->count(),
        ];
    }
}
