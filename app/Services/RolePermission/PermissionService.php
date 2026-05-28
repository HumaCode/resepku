<?php

namespace App\Services\RolePermission;

use App\Repositories\RolePermission\PermissionRepositoryInterface;
use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all permissions.
     */
    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Get paginated permissions based on filters.
     */
    public function getPaginatedPermissions(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->permissionRepository->getPaginated($filters, $perPage);
    }

    /**
     * Create a new permission.
     */
    public function createPermission(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    /**
     * Update an existing permission.
     */
    public function updatePermission(Permission $permission, array $data): Permission
    {
        return $this->permissionRepository->update($permission, $data);
    }

    /**
     * Delete a permission.
     */
    public function deletePermission(Permission $permission): bool
    {
        return $this->permissionRepository->delete($permission);
    }

    /**
     * Toggle the active status of a permission.
     */
    public function toggleActivePermission(Permission $permission): Permission
    {
        return $this->permissionRepository->toggleActive($permission);
    }
}
