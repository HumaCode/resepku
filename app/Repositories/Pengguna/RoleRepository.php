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
}
