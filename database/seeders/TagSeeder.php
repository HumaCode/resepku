<?php

namespace Database\Seeders;

use App\Models\MasterData\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'rendang',
                'slug' => 'rendang',
                'color' => '#ef4444',
                'is_hot' => '1',
                'is_active' => '1',
                'views' => 342,
            ],
            [
                'name' => 'nasi goreng',
                'slug' => 'nasi-goreng',
                'color' => '#f59e0b',
                'is_hot' => '1',
                'is_active' => '1',
                'views' => 287,
            ],
            [
                'name' => 'ayam',
                'slug' => 'ayam',
                'color' => '#e85d26',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 215,
            ],
            [
                'name' => 'kue',
                'slug' => 'kue',
                'color' => '#ec4899',
                'is_hot' => '1',
                'is_active' => '1',
                'views' => 198,
            ],
            [
                'name' => 'seafood',
                'slug' => 'seafood',
                'color' => '#10b981',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 176,
            ],
            [
                'name' => 'vegetarian',
                'slug' => 'vegetarian',
                'color' => '#22c55e',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 143,
            ],
            [
                'name' => 'pedas',
                'slug' => 'pedas',
                'color' => '#dc2626',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 134,
            ],
            [
                'name' => 'tradisional',
                'slug' => 'tradisional',
                'color' => '#7c3aed',
                'is_hot' => '1',
                'is_active' => '1',
                'views' => 118,
            ],
            [
                'name' => 'dessert',
                'slug' => 'dessert',
                'color' => '#f472b6',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 109,
            ],
            [
                'name' => 'diet sehat',
                'slug' => 'diet-sehat',
                'color' => '#14b8a6',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 97,
            ],
            [
                'name' => 'mudah dibuat',
                'slug' => 'mudah-dibuat',
                'color' => '#0ea5e9',
                'is_hot' => '1',
                'is_active' => '1',
                'views' => 63,
            ],
            [
                'name' => 'berkuah',
                'slug' => 'berkuah',
                'color' => '#06b6d4',
                'is_hot' => '0',
                'is_active' => '1',
                'views' => 52,
            ],
            [
                'name' => 'fermentasi',
                'slug' => 'fermentasi',
                'color' => '#94a3b8',
                'is_hot' => '0',
                'is_active' => '0',
                'views' => 28,
            ],
            [
                'name' => 'raw food',
                'slug' => 'raw-food',
                'color' => '#94a3b8',
                'is_hot' => '0',
                'is_active' => '0',
                'views' => 14,
            ],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
