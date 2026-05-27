<?php

namespace App\Services\Pengguna;

use App\Repositories\Pengguna\RoleRepositoryInterface;
use Illuminate\Support\Collection;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllRoles();
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return \App\Models\Role
     */
    public function createRole(array $data)
    {
        return $this->roleRepository->createRole($data);
    }

    /**
     * Update an existing role.
     *
     * @param \App\Models\Role $role
     * @param array $data
     * @return \App\Models\Role
     */
    public function updateRole($role, array $data)
    {
        return $this->roleRepository->updateRole($role, $data);
    }

    /**
     * Delete a role.
     *
     * @param \App\Models\Role $role
     * @return bool|null
     */
    public function deleteRole($role)
    {
        return $this->roleRepository->deleteRole($role);
    }

    /**
     * Sync permissions for a role.
     *
     * @param string $roleSlug
     * @param array $permissionNames
     * @return void
     */
    public function syncPermissionsForRole(string $roleSlug, array $permissionNames): void
    {
        // Map member to user role
        if ($roleSlug === 'member') {
            $roleSlug = 'user';
        }

        $this->roleRepository->syncPermissionsForRole($roleSlug, $permissionNames);
    }
}
