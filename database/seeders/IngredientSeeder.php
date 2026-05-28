<?php

namespace Database\Seeders;

use App\Models\MasterData\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            [
                'emoji' => '🧅',
                'name' => 'Bawang Merah',
                'slug' => 'bawang-merah',
                'category' => 'bumbu',
                'default_unit' => 'siung, gram',
                'description' => 'Bawang merah segar lokal.',
                'is_active' => '1',
                'views' => 125,
            ],
            [
                'emoji' => '🧄',
                'name' => 'Bawang Putih',
                'slug' => 'bawang-putih',
                'category' => 'bumbu',
                'default_unit' => 'siung, gram',
                'description' => 'Bawang putih segar.',
                'is_active' => '1',
                'views' => 98,
            ],
            [
                'emoji' => '🌶️',
                'name' => 'Cabai Merah',
                'slug' => 'cabai-merah',
                'category' => 'bumbu',
                'default_unit' => 'buah, gram',
                'description' => 'Cabai merah keriting pedas.',
                'is_active' => '1',
                'views' => 84,
            ],
            [
                'emoji' => '🍗',
                'name' => 'Ayam',
                'slug' => 'ayam',
                'category' => 'daging',
                'default_unit' => 'gram, ekor',
                'description' => 'Daging ayam segar (potongan atau utuh).',
                'is_active' => '1',
                'views' => 142,
            ],
            [
                'emoji' => '🥩',
                'name' => 'Daging Sapi',
                'slug' => 'daging-sapi',
                'category' => 'daging',
                'default_unit' => 'gram, kg',
                'description' => 'Daging sapi segar berkualitas tinggi.',
                'is_active' => '1',
                'views' => 64,
            ],
            [
                'emoji' => '🥬',
                'name' => 'Bayam',
                'slug' => 'bayam',
                'category' => 'sayuran',
                'default_unit' => 'ikat, gram',
                'description' => 'Sayur bayam hijau segar.',
                'is_active' => '1',
                'views' => 38,
            ],
            [
                'emoji' => '🥔',
                'name' => 'Kentang',
                'slug' => 'kentang',
                'category' => 'karbohidrat',
                'default_unit' => 'buah, gram',
                'description' => 'Kentang dieng berkualitas baik.',
                'is_active' => '1',
                'views' => 45,
            ],
            [
                'emoji' => '🥕',
                'name' => 'Wortel',
                'slug' => 'wortel',
                'category' => 'sayuran',
                'default_unit' => 'buah, gram',
                'description' => 'Wortel manis segar kaya vitamin A.',
                'is_active' => '1',
                'views' => 52,
            ],
            [
                'emoji' => '🥥',
                'name' => 'Santan',
                'slug' => 'santan',
                'category' => 'lainnya',
                'default_unit' => 'ml, bungkus',
                'description' => 'Santan kelapa murni cair.',
                'is_active' => '1',
                'views' => 110,
            ],
            [
                'emoji' => '🌾',
                'name' => 'Tepung Terigu',
                'slug' => 'tepung-terigu',
                'category' => 'karbohidrat',
                'default_unit' => 'gram, kg',
                'description' => 'Tepung terigu protein sedang.',
                'is_active' => '1',
                'views' => 77,
            ],
            [
                'emoji' => '🥚',
                'name' => 'Telur',
                'slug' => 'telur',
                'category' => 'susu',
                'default_unit' => 'butir',
                'description' => 'Telur ayam ras segar.',
                'is_active' => '1',
                'views' => 156,
            ],
            [
                'emoji' => '🥛',
                'name' => 'Susu Cair',
                'slug' => 'susu-cair',
                'category' => 'susu',
                'default_unit' => 'ml, liter',
                'description' => 'Susu cair segar UHT.',
                'is_active' => '1',
                'views' => 49,
            ],
            [
                'emoji' => '🐟',
                'name' => 'Ikan Tongkol',
                'slug' => 'ikan-tongkol',
                'category' => 'seafood',
                'default_unit' => 'gram, ekor',
                'description' => 'Ikan tongkol segar hasil tangkapan nelayan.',
                'is_active' => '1',
                'views' => 31,
            ],
            [
                'emoji' => '🦐',
                'name' => 'Udang',
                'slug' => 'udang',
                'category' => 'seafood',
                'default_unit' => 'gram, ekor',
                'description' => 'Udang windu atau vaname segar.',
                'is_active' => '1',
                'views' => 67,
            ],
            [
                'emoji' => '🥭',
                'name' => 'Mangga',
                'slug' => 'mangga',
                'category' => 'buah',
                'default_unit' => 'buah, gram',
                'description' => 'Mangga harum manis matang pohon.',
                'is_active' => '1',
                'views' => 28,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(
                ['slug' => $ingredient['slug']],
                $ingredient
            );
        }
    }
}
