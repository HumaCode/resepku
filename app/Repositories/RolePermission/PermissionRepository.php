<?php

namespace App\Repositories\RolePermission;

use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Get all permissions.
     */
    public function getAll(): Collection
    {
        return Permission::all();
    }

    /**
     * Get paginated permissions based on search & filter parameters.
     */
    public function getPaginated(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Permission::query();

        // Search by keyword
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Filter by active status
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $statusVal = $filters['status'] === 'active' ? '1' : '0';
            $query->where('is_active', $statusVal);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        
        $validColumns = ['name', 'guard_name', 'created_at'];
        if (in_array($sortBy, $validColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new permission.
     */
    public function create(array $data): Permission
    {
        $data['guard_name'] = $data['guard_name'] ?? 'web';
        $data['is_active'] = $data['is_active'] ?? '1';

        $permission = Permission::create($data);

        // Forget spatie permission cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $permission;
    }

    /**
     * Find a permission by ID.
     */
    public function find(string $id): ?Permission
    {
        return Permission::find($id);
    }

    /**
     * Update an existing permission.
     */
    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);

        // Forget spatie permission cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $permission;
    }

    /**
     * Delete a permission.
     */
    public function delete(Permission $permission): bool
    {
        $deleted = (bool) $permission->delete();

        // Forget spatie permission cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $deleted;
    }

    /**
     * Toggle the active status of a permission.
     */
    public function toggleActive(Permission $permission): Permission
    {
        $newStatus = $permission->is_active === '1' ? '0' : '1';
        $permission->update(['is_active' => $newStatus]);

        // Forget spatie permission cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $permission;
    }
}
