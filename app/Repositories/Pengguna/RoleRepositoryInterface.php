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
}
