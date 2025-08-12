<?php

namespace App\Services\System;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Retrieve a paginated list of permissions.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPermissions(Request $request): LengthAwarePaginator
    {
        return Permission::query()
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhere('group', "%{$search}%")
                    ->orWhere('title', "%{$search}%")
                );
            })
            ->when($request->filled('group'), fn($q) => $q->where('group', $request->group))
            ->when($request->filled('guard_name'), fn($q) => $q->where('guard_name', $request->guard_name))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'group', 'title', 'guard_name', 'created_at', 'updated_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return Permission
     * @throws Exception
     */
    public function createPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            $permission = Permission::create([
                'name' => $data['name'],
                'group' => $data['group'],
                'title' => $data['title'],
                'guard_name' => $data['guard_name'] ?? 'web',
            ]);

            return $permission;
        });
    }

    /**
     * Find a permission by ID.
     *
     * @param int $id
     * @return Permission
     */
    public function findPermission(int $id): Permission
    {
        return Permission::findOrFail($id);
    }

    /**
     * Update an existing permission.
     *
     * @param Permission $permission
     * @param array $data
     * @return Permission
     * @throws Exception
     */
    public function updatePermission(Permission $permission, array $data): Permission
    {
        return DB::transaction(function () use ($permission, $data) {
            $permission->update([
                'name' => $data['name'],
                'group' => $data['group'],
                'title' => $data['title'],
                'guard_name' => $data['guard_name'] ?? 'web',
            ]);

            return $permission;
        });
    }

    /**
     * Delete a permission.
     *
     * @param Permission $permission
     * @return bool|null
     * @throws Exception
     */
    public function deletePermission(Permission $permission): ?bool
    {
        return DB::transaction(fn() => $permission->delete());
    }

    /**
     * Bulk delete permissions.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeletePermissions(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = 0;
            foreach ($ids as $id) {
                $permission = Permission::find($id);
                if ($permission) {
                    $permission->delete();
                    $deletedCount++;
                }
            }
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No permissions found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for permissions.
     *
     * @return array
     */
    public function getPermissionStatistics(): array
    {
        return [
            'total' => Permission::query()->count(),
            'web_guard' => Permission::query()->where('guard_name', 'web')->count(),
            'api_guard' => Permission::query()->where('guard_name', 'api')->count(),
            'this_month' => Permission::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Permission::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Permission::query()->whereDate('created_at', today())->count(),
        ];
    }
}
