<?php

namespace App\Repositories\Pengguna;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAllRoles(): Collection;

    /**
     * Create a new role.
     *
     * @param array $data
     * @return \App\Models\Role
     */
    public function createRole(array $data);

    /**
     * Update an existing role.
     *
     * @param \App\Models\Role $role
     * @param array $data
     * @return \App\Models\Role
     */
    public function updateRole($role, array $data);

    /**
     * Delete a role.
     *
     * @param \App\Models\Role $role
     * @return bool|null
     */
    public function deleteRole($role);

    /**
     * Sync permissions for a specific role by its slug.
     *
     * @param string $roleSlug
     * @param array $permissionNames
     * @return void
     */
    public function syncPermissionsForRole(string $roleSlug, array $permissionNames): void;
}
