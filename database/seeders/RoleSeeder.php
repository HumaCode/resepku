<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'dev',
                'slug' => 'dev',
                'type_role' => 'system',
                'description' => 'memiliki akses penuh',
                'is_active' => '1',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin',
                'slug' => 'admin',
                'type_role' => 'system',
                'description' => 'memiliki akses tertentu',
                'is_active' => '1',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user',
                'slug' => 'user',
                'type_role' => 'system',
                'description' => 'memiliki akses terbatas',
                'is_active' => '1',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                $roleData
            );
        }
    }
}
