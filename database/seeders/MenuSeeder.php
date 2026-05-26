<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Konfigurasi\Menu;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasMenuPermission;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // MASTER DATA
            [
                'name' => 'Project',
                'url' => 'projects',
                'category' => 'MASTER DATA',
                'icon' => 'bi-folder2-open',
                'is_active' => '1',
                'orders' => 1,
            ],
            [
                'name' => 'Kategori',
                'url' => 'categories',
                'category' => 'MASTER DATA',
                'icon' => 'bi-grid',
                'is_active' => '1',
                'orders' => 2,
            ],
            [
                'name' => 'Tags',
                'url' => 'tags',
                'category' => 'MASTER DATA',
                'icon' => 'bi-tags',
                'is_active' => '1',
                'orders' => 3,
            ],
            [
                'name' => 'Bahan Makanan',
                'url' => 'ingredients',
                'category' => 'MASTER DATA',
                'icon' => 'bi-egg-fried',
                'is_active' => '1',
                'orders' => 4,
            ],

            // KONTEN
            [
                'name' => 'Resep',
                'url' => 'recipes',
                'category' => 'KONTEN',
                'icon' => 'bi-book-half',
                'is_active' => '1',
                'orders' => 5,
            ],
            [
                'name' => 'Moderasi',
                'url' => 'moderations',
                'category' => 'KONTEN',
                'icon' => 'bi-shield-check',
                'is_active' => '1',
                'orders' => 6,
            ],
            [
                'name' => 'Koleksi',
                'url' => 'collections',
                'category' => 'KONTEN',
                'icon' => 'bi-bookmarks',
                'is_active' => '1',
                'orders' => 7,
            ],
            [
                'name' => 'Komentar',
                'url' => 'comments',
                'category' => 'KONTEN',
                'icon' => 'bi-chat-square-text',
                'is_active' => '1',
                'orders' => 8,
            ],

            // PENGGUNA
            [
                'name' => 'User',
                'url' => 'users',
                'category' => 'PENGGUNA',
                'icon' => 'bi-people',
                'is_active' => '1',
                'orders' => 9,
            ],
            [
                'name' => 'Peran dan Akses',
                'url' => 'roles-permissions-management',
                'category' => 'PENGGUNA',
                'icon' => 'bi-person-badge',
                'is_active' => '1',
                'orders' => 10,
            ],
            [
                'name' => 'Laporan User',
                'url' => 'user-reports',
                'category' => 'PENGGUNA',
                'icon' => 'bi-flag',
                'is_active' => '1',
                'orders' => 11,
            ],
            [
                'name' => 'Profil',
                'url' => 'profile',
                'category' => 'PENGGUNA',
                'icon' => 'bi-person-circle',
                'is_active' => '1',
                'orders' => 12,
            ],

            // ROLE PERMISSION
            [
                'name' => 'Role',
                'url' => 'roles',
                'category' => 'ROLE PERMISSION',
                'icon' => 'bi-shield-lock',
                'is_active' => '1',
                'orders' => 13,
            ],
            [
                'name' => 'Permission',
                'url' => 'permissions',
                'category' => 'ROLE PERMISSION',
                'icon' => 'bi-key',
                'is_active' => '1',
                'orders' => 14,
            ],
            [
                'name' => 'Menu',
                'url' => 'menus',
                'category' => 'ROLE PERMISSION',
                'icon' => 'bi-list-ul',
                'is_active' => '1',
                'orders' => 15,
            ],

            // ANALITIK
            [
                'name' => 'Statistik',
                'url' => 'statistics',
                'category' => 'ANALITIK',
                'icon' => 'bi-graph-up',
                'is_active' => '1',
                'orders' => 16,
            ],
            [
                'name' => 'Trending',
                'url' => 'trending',
                'category' => 'ANALITIK',
                'icon' => 'bi-fire',
                'is_active' => '1',
                'orders' => 17,
            ],

            // SISTEM
            [
                'name' => 'Pengaturan',
                'url' => 'settings',
                'category' => 'SISTEM',
                'icon' => 'bi-gear',
                'is_active' => '1',
                'orders' => 18,
            ],
            [
                'name' => 'Bantuan',
                'url' => 'help',
                'category' => 'SISTEM',
                'icon' => 'bi-question-circle',
                'is_active' => '1',
                'orders' => 19,
            ],
        ];

        foreach ($menus as $menuData) {
            $menu = Menu::updateOrCreate(
                ['url' => $menuData['url']],
                $menuData
            );

            // Tentukan custom permission sesuai request
            $customPermissions = null;
            if ($menu->url === 'roles-permissions-management') {
                $customPermissions = ['menu', 'create', 'read', 'show', 'update', 'delete', 'akses'];
            } elseif ($menu->url === 'users') {
                $customPermissions = ['menu', 'create', 'read', 'show', 'update', 'delete', 'activate'];
            }

            // Assign permissions ke menu dan ke role dev
            $this->attachMenupermission($menu, $customPermissions, ['dev']);
        }

        // Clear menu cache to make sure updates are visible immediately
        Cache::forget('menus_data');
        Cache::forget('menus_url_list');
    }
}
