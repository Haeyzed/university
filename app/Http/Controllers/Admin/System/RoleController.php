<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\RoleRequest;
use App\Http\Resources\System\RoleResource;
use App\Services\System\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Role
 */
class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a paginated listing of the Role resources.
     *
     * @param Request $request
     * @return RoleResource|JsonResponse
     */
    public function index(Request $request): RoleResource|JsonResponse
    {
        $roles = $this->roleService->getRoles($request);
        return Response::paginated(RoleResource::collection($roles), 'Roles retrieved successfully.');
    }

    /**
     * Store a newly created Role resource in storage.
     *
     * @param RoleRequest $request The validated request.
     * @return RoleResource|JsonResponse
     */
    public function store(RoleRequest $request): RoleResource|JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());
            return Response::created(new RoleResource($role->load('permissions')), 'Role created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create role: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Role resource.
     *
     * @param int $id The Role ID.
     * @return RoleResource|JsonResponse
     */
    public function show(int $id): RoleResource|JsonResponse
    {
        try {
            $role = $this->roleService->findRole($id);
            return Response::success(new RoleResource($role), 'Role retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Role not found.');
        }
    }

    /**
     * Update the specified Role resource in storage.
     *
     * @param RoleRequest $request The validated request.
     * @param int $id The Role ID.
     * @return RoleResource|JsonResponse
     */
    public function update(RoleRequest $request, int $id): RoleResource|JsonResponse
    {
        try {
            $role = $this->roleService->findRole($id);
            $updatedRole = $this->roleService->updateRole($role, $request->validated());
            return Response::updated(new RoleResource($updatedRole), 'Role updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Role not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update role: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Role resource from storage.
     *
     * @param int $id The Role ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $role = $this->roleService->findRole($id);
            $this->roleService->deleteRole($role);
            return Response::deleted('Role deleted successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Role not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete role: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete roles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:roles,id'
        ]);

        try {
            $deletedCount = $this->roleService->bulkDeleteRoles($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} roles deleted successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No roles found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete roles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for roles.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->roleService->getRoleStatistics();
            return Response::success($stats, 'Role statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve role statistics: ' . $e->getMessage(), 500);
        }
    }
}
