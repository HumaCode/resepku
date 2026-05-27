<?php

namespace App\Repositories\Pengguna;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAllRoles(): Collection
    {
        return Role::withCount(['users', 'permissions'])->get();
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data)
    {
        $data['guard_name'] = $data['guard_name'] ?? 'web';
        return Role::create($data);
    }

    /**
     * Update an existing role.
     *
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function updateRole($role, array $data)
    {
        $role->update($data);
        return $role;
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return bool|null
     */
    public function deleteRole($role)
    {
        return $role->delete();
    }

    /**
     * Sync permissions for a specific role by its slug.
     *
     * @param string $roleSlug
     * @param array $permissionNames
     * @return void
     */
    public function syncPermissionsForRole(string $roleSlug, array $permissionNames): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            // Find or create the permissions to prevent "PermissionDoesNotExist" exception
            $permissions = [];
            foreach ($permissionNames as $name) {
                $permissions[] = \App\Models\Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web'
                ]);
            }
            
            // Sync permissions for this role
            $role->syncPermissions($permissions);
        }
    }
}
