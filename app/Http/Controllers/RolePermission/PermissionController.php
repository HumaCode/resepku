<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Services\RolePermission\PermissionService;
use App\Http\Requests\RolePermission\StorePermissionRequest;
use App\Http\Requests\RolePermission\UpdatePermissionRequest;
use App\Http\Resources\RolePermission\PermissionResource;
use App\Models\Permission;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $all = $this->permissionService->getAllPermissions();

        $statistics = [
            'total' => $all->count(),
            'active' => $all->where('is_active', '1')->count(),
            'inactive' => $all->where('is_active', '0')->count(),
            'guards' => $all->pluck('guard_name')->unique()->count(),
        ];

        return view('pages.pengguna.permission.index', [
            'statistics' => $statistics,
        ]);
    }

    /**
     * Fetch JSON list of resource (AJAX).
     */
    public function list(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by'),
            'sort_order' => $request->query('sort_order'),
        ];
        $perPage = $request->query('per_page', 12);

        $paginated = $this->permissionService->getPaginatedPermissions($filters, $perPage);
        $data = new \App\Http\Resources\PaginateResource($paginated, PermissionResource::class);

        // Fetch absolute stats for synchronization
        $all = $this->permissionService->getAllPermissions();
        $statistics = [
            'total' => $all->count(),
            'active' => $all->where('is_active', '1')->count(),
            'inactive' => $all->where('is_active', '0')->count(),
            'guards' => $all->pluck('guard_name')->unique()->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data permission berhasil dimuat.',
            'data' => $data->resolve(),
            'statistics' => $statistics,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $permission = $this->permissionService->createPermission($validated);
        $data = new PermissionResource($permission);

        return ResponseHelper::jsonResponse(true, 'Permission baru berhasil disimpan.', $data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $updated = $this->permissionService->updatePermission($permission, $validated);
        $data = new PermissionResource($updated);

        return ResponseHelper::jsonResponse(true, 'Permission berhasil diperbarui.', $data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $this->permissionService->deletePermission($permission);
        return ResponseHelper::jsonResponse(true, 'Permission berhasil dihapus.', null, 200);
    }

    /**
     * Toggle active status of permission.
     */
    public function toggleActive(Permission $permission): JsonResponse
    {
        $updated = $this->permissionService->toggleActivePermission($permission);
        $data = new PermissionResource($updated);

        return ResponseHelper::jsonResponse(true, 'Status permission berhasil diubah.', $data, 200);
    }
}
