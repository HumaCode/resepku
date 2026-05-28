<?php

namespace Database\Seeders;

use App\Models\MasterData\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentCategories = [
            [
                'name' => 'Makanan Berat',
                'slug' => 'makanan-berat',
                'icon' => '🍛',
                'description' => 'Kategori untuk hidangan utama bertepung, berkarbohidrat seperti nasi, mie, roti dan sejenisnya.',
                'is_active' => '1',
                'orders' => 1,
                'views' => 14200,
            ],
            [
                'name' => 'Minuman',
                'slug' => 'minuman',
                'icon' => '🥤',
                'description' => 'Semua jenis minuman segar, jus buah, teh, kopi, dan minuman tradisional khas Indonesia.',
                'is_active' => '1',
                'orders' => 2,
                'views' => 8500,
            ],
            [
                'name' => 'Dessert',
                'slug' => 'dessert',
                'icon' => '🍰',
                'description' => 'Makanan penutup manis seperti kue, pudding, es krim, dan camilan manis lainnya.',
                'is_active' => '1',
                'orders' => 3,
                'views' => 11300,
            ],
            [
                'name' => 'Seafood',
                'slug' => 'seafood',
                'icon' => '🦐',
                'description' => 'Aneka hidangan berbahan dasar hasil laut seperti ikan, udang, cumi, dan kerang.',
                'is_active' => '1',
                'orders' => 5,
                'views' => 7800,
            ],
            [
                'name' => 'Makanan Diet',
                'slug' => 'makanan-diet',
                'icon' => '🥗',
                'description' => 'Hidangan sehat rendah kalori yang cocok untuk program diet dan gaya hidup sehat.',
                'is_active' => '0',
                'orders' => 6,
                'views' => 2100,
            ],
        ];

        $createdParents = [];
        foreach ($parentCategories as $cat) {
            $createdParents[$cat['slug']] = Category::create($cat);
        }

        // Seed Sub-Categories (Children)
        $childCategories = [
            [
                'name' => 'Kue Tradisional',
                'slug' => 'kue-tradisional',
                'icon' => '🧁',
                'description' => 'Aneka kue tradisional nusantara seperti klepon, onde-onde, dan kue basah lainnya.',
                'is_active' => '1',
                'orders' => 4,
                'views' => 4100,
                'parent_id' => $createdParents['dessert']->id,
            ],
        ];

        foreach ($childCategories as $cat) {
            Category::create($cat);
        }
    }
}
