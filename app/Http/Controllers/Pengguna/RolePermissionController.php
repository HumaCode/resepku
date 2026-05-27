<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Services\Pengguna\RoleService;
use App\Http\Resources\Pengguna\RoleResource;
use App\Helpers\ResponseHelper;
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
}
