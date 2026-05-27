<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Services\Pengguna\RoleService;
use App\Http\Resources\Pengguna\RoleResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Pengguna\StoreRoleRequest;
use App\Http\Requests\Pengguna\UpdateRoleRequest;
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
        return view('pages.pengguna.role-permission.index');
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
}
