<?php

namespace App\Services\System;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\SlugHelper;

class RoleService
{
    /**
     * Retrieve a paginated list of roles.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getRoles(Request $request): LengthAwarePaginator
    {
        return Role::query()
            ->with('permissions')
            ->when($search = $request->input('search'), function ($q) use ($search) {
                $q->whereLike(fn($q) => $q->where('name', "%{$search}%")
                    ->orWhere('slug', "%{$search}%")
                );
            })
            ->when($request->filled('guard_name'), fn($q) => $q->where('guard_name', $request->guard_name))
            ->when(
                $request->filled('sort_by') && in_array($request->sort_by, ['id', 'name', 'slug', 'guard_name', 'created_at', 'updated_at']),
                fn($q) => $q->orderBy($request->sort_by, $request->input('sort_direction', 'desc')),
                fn($q) => $q->orderByDesc('id')
            )
            ->paginate(min((int) $request->input('per_page', 15), 100));
    }

    /**
     * Create a new role and assign permissions.
     *
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $slug = SlugHelper::generateUniqueSlug($data['slug'] ?? $data['name'], Role::class);

            $role = Role::create([
                'name' => $data['name'],
                'slug' => $slug,
                'guard_name' => $data['guard_name'] ?? 'web',
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role;
        });
    }

    /**
     * Find a role by ID.
     *
     * @param int $id
     * @return Role
     */
    public function findRole(int $id): Role
    {
        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * Update an existing role and sync permissions.
     *
     * @param Role $role
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $slug = $data['slug'] ?? $data['name'];
            if ($slug !== $role->slug) {
                $role->slug = SlugHelper::generateUniqueSlug($slug, Role::class, $role->id);
            }

            $role->update([
                'name' => $data['name'],
                'slug' => $role->slug,
                'guard_name' => $data['guard_name'] ?? 'web',
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            } else {
                $role->syncPermissions([]); // Detach all permissions if none provided
            }

            return $role->load('permissions');
        });
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return bool|null
     * @throws Exception
     */
    public function deleteRole(Role $role): ?bool
    {
        return DB::transaction(fn() => $role->delete());
    }

    /**
     * Bulk delete roles.
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDeleteRoles(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $deletedCount = 0;
            foreach ($ids as $id) {
                $role = Role::find($id);
                if ($role) {
                    $role->delete();
                    $deletedCount++;
                }
            }
            if ($deletedCount === 0) {
                throw new ModelNotFoundException('No roles found with the provided IDs.');
            }
            return $deletedCount;
        });
    }

    /**
     * Get statistics for roles.
     *
     * @return array
     */
    public function getRoleStatistics(): array
    {
        return [
            'total' => Role::query()->count(),
            'web_guard' => Role::query()->where('guard_name', 'web')->count(),
            'api_guard' => Role::query()->where('guard_name', 'api')->count(),
            'this_month' => Role::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Role::query()->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            'today' => Role::query()->whereDate('created_at', today())->count(),
        ];
    }
}
