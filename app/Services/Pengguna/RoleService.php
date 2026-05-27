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
}
