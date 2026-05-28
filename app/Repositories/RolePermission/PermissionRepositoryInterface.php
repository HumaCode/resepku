<?php

namespace App\Repositories\RolePermission;

use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    /**
     * Get all permissions.
     */
    public function getAll(): Collection;

    /**
     * Get paginated permissions based on search & filter parameters.
     */
    public function getPaginated(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Create a new permission.
     */
    public function create(array $data): Permission;

    /**
     * Find a permission by ID.
     */
    public function find(string $id): ?Permission;

    /**
     * Update an existing permission.
     */
    public function update(Permission $permission, array $data): Permission;

    /**
     * Delete a permission.
     */
    public function delete(Permission $permission): bool;

    /**
     * Toggle the active status of a permission.
     */
    public function toggleActive(Permission $permission): Permission;
}
