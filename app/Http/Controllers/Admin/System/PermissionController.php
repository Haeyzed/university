<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\PermissionRequest;
use App\Http\Resources\System\PermissionResource;
use App\Services\System\PermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Permission
 */
class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a paginated listing of the Permission resources.
     *
     * @param Request $request
     * @return PermissionResource|JsonResponse
     */
    public function index(Request $request): PermissionResource|JsonResponse
    {
        $permissions = $this->permissionService->getPermissions($request);
        return Response::paginated(PermissionResource::collection($permissions), 'Permissions retrieved successfully.');
    }

    /**
     * Store a newly created Permission resource in storage.
     *
     * @param PermissionRequest $request The validated request.
     * @return PermissionResource|JsonResponse
     */
    public function store(PermissionRequest $request): PermissionResource|JsonResponse
    {
        try {
            $permission = $this->permissionService->createPermission($request->validated());
            return Response::created(new PermissionResource($permission), 'Permission created successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to create permission: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified Permission resource.
     *
     * @param int $id The Permission ID.
     * @return PermissionResource|JsonResponse
     */
    public function show(int $id): PermissionResource|JsonResponse
    {
        try {
            $permission = $this->permissionService->findPermission($id);
            return Response::success(new PermissionResource($permission), 'Permission retrieved successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Permission not found.');
        }
    }

    /**
     * Update the specified Permission resource in storage.
     *
     * @param PermissionRequest $request The validated request.
     * @param int $id The Permission ID.
     * @return PermissionResource|JsonResponse
     */
    public function update(PermissionRequest $request, int $id): PermissionResource|JsonResponse
    {
        try {
            $permission = $this->permissionService->findPermission($id);
            $updatedPermission = $this->permissionService->updatePermission($permission, $request->validated());
            return Response::updated(new PermissionResource($updatedPermission), 'Permission updated successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Permission not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to update permission: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified Permission resource from storage.
     *
     * @param int $id The Permission ID.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $permission = $this->permissionService->findPermission($id);
            $this->permissionService->deletePermission($permission);
            return Response::deleted('Permission deleted successfully.');
        } catch (ModelNotFoundException) {
            return Response::notFound('Permission not found.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete permission: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete permissions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:permissions,id'
        ]);

        try {
            $deletedCount = $this->permissionService->bulkDeletePermissions($request->input('ids'));
            return Response::success(['deleted_count' => $deletedCount], "{$deletedCount} permissions deleted successfully.");
        } catch (ModelNotFoundException) {
            return Response::notFound('No permissions found with the provided IDs.');
        } catch (Throwable $e) {
            return Response::error('Failed to delete permissions: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get statistics for permissions.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->permissionService->getPermissionStatistics();
            return Response::success($stats, 'Permission statistics retrieved successfully.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve permission statistics: ' . $e->getMessage(), 500);
        }
    }
}
