<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Services\Pengguna\RoleService;
use App\Http\Resources\Pengguna\RoleResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Pengguna\StoreRoleRequest;
use App\Http\Requests\Pengguna\UpdateRoleRequest;
use App\Http\Requests\Pengguna\SyncPermissionsRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RolePermissionController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display the roles & permissions management page.
     */
    public function index()
    {
        $roles = \App\Models\Role::where('is_active', '1')
            ->with('permissions')
            ->withCount('users')
            ->get();

        $roles = $roles->sortBy(function ($role) {
            if ($role->slug === 'dev') return 1;
            if ($role->slug === 'admin') return 2;
            if ($role->slug === 'user') return 99;
            return 10;
        });

        return view('pages.pengguna.role-permission.index', compact('roles'));
    }

    /**
     * Get all roles data via AJAX.
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = $this->roleService->getAllRoles();
        $data = RoleResource::collection($roles);

        return ResponseHelper::jsonResponse(true, 'Data role berhasil diambil', $data, 200);
    }

    /**
     * Store a newly created role.
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());
        $data = new RoleResource($role);

        return ResponseHelper::jsonResponse(true, 'Role baru berhasil disimpan', $data, 201);
    }

    /**
     * Update an existing role.
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        // Protect system roles from having their slug modified
        if (in_array($role->slug, ['dev', 'admin', 'user'])) {
            if ($request->input('slug') !== $role->slug) {
                return ResponseHelper::jsonResponse(false, 'Role bawaan sistem tidak boleh diubah slug-nya.', null, 422);
            }
        }

        $updatedRole = $this->roleService->updateRole($role, $request->validated());
        $data = new RoleResource($updatedRole);

        return ResponseHelper::jsonResponse(true, 'Role berhasil diperbarui', $data, 200);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        // Protect system roles from deletion
        if (in_array($role->slug, ['dev', 'admin', 'user'])) {
            return ResponseHelper::jsonResponse(false, 'Role bawaan sistem tidak dapat dihapus.', null, 422);
        }

        $this->roleService->deleteRole($role);

        return ResponseHelper::jsonResponse(true, 'Role berhasil dihapus', null, 200);
    }

    /**
     * Sync permissions for roles.
     *
     * @param SyncPermissionsRequest $request
     * @return JsonResponse
     */
    public function syncPermissions(SyncPermissionsRequest $request): JsonResponse
    {
        $matrix = $request->input('matrix', []);

        foreach ($matrix as $roleSlug => $permissions) {
            $this->roleService->syncPermissionsForRole($roleSlug, $permissions);
        }

        // Clear Spatie Permission cache to reflect changes
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return ResponseHelper::jsonResponse(true, 'Perubahan izin berhasil disimpan!', null, 200);
    }
}
