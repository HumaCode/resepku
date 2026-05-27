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
                'description' => 'Developer/System Administrator - Memiliki otoritas dan hak akses penuh ke seluruh modul, pengaturan sistem, konfigurasi server, serta manajemen basis data aplikasi.',
                'is_active' => '1',
                'color' => '#f59e0b',
                'icon' => '👑',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin',
                'slug' => 'admin',
                'type_role' => 'system',
                'description' => 'Administrator - Memiliki hak akses penuh untuk mengelola data operasional, memoderasi postingan resep, serta mengelola data pengguna reguler tanpa akses ke konfigurasi sistem/developer.',
                'is_active' => '1',
                'color' => '#e85d26',
                'icon' => '🛡️',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user',
                'slug' => 'user',
                'type_role' => 'system',
                'description' => 'Regular User - Pengguna terdaftar dengan akses terbatas untuk menjelajahi resep, membuat/mengedit resep pribadi, serta memberikan interaksi (like/comment) pada platform.',
                'is_active' => '1',
                'color' => '#64748b',
                'icon' => '👤',
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
