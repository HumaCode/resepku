<?php

namespace Database\Seeders;

use App\Models\Konten\Recipe;
use App\Models\MasterData\Category;
use App\Models\MasterData\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get primary models for relations
        $user = User::where('username', 'admin')->first() ?? User::first();
        $category = Category::where('slug', 'makanan-berat')->first() ?? Category::first();

        // 1. Rendang Daging Sapi
        $recipe1 = Recipe::create([
            'user_id' => $user?->id,
            'category_id' => $category?->id,
            'title' => 'Rendang Daging Sapi Padang Asli',
            'slug' => 'rendang-daging-sapi-padang-asli',
            'description' => 'Resep rendang daging sapi khas Minang asli Padang yang empuk, gurih, dan tahan lama.',
            'content' => '<p>Rendang adalah masakan khas Minangkabau yang sangat populer. Masakan ini menggunakan aneka bumbu rempah-rempah yang dimasak perlahan hingga menghasilkan daging yang empuk dan bumbu yang meresap sempurna.</p>',
            'difficulty' => 'sulit',
            'prep_time' => 30,
            'cook_time' => 180,
            'servings' => 6,
            'calories' => 450,
            'protein' => 28,
            'fat' => 32,
            'carbs' => 12,
            'fiber' => 2,
            'sugar' => 3,
            'is_featured' => '1',
            'enable_comments' => '1',
            'enable_ratings' => '1',
            'status' => 'published',
            'meta_title' => 'Resep Rendang Daging Sapi Padang Asli Enak & Empuk',
            'meta_description' => 'Resep cara membuat rendang daging sapi Padang asli yang gurih, hitam pekat, bumbu melimpah, dan dagingnya sangat empuk.',
            'views' => 12840,
            'rating' => 4.90,
        ]);

        $recipe1->videos()->createMany([
            ['video_url' => 'https://www.youtube.com/embed/Ppp5_S1Xmhs', 'video_provider' => 'youtube', 'orders' => 1],
            ['video_url' => 'https://www.instagram.com/reel/C8aBcd1/', 'video_provider' => 'instagram', 'orders' => 2],
        ]);

        $recipe1->ingredients()->createMany([
            ['name' => 'Daging Sapi (potong kotak)', 'amount' => '1', 'unit' => 'kg'],
            ['name' => 'Santan Kental (dari 3 butir kelapa)', 'amount' => '1000', 'unit' => 'ml'],
            ['name' => 'Serai (memarkan)', 'amount' => '2', 'unit' => 'batang'],
            ['name' => 'Daun Kunyit (ikat simpul)', 'amount' => '1', 'unit' => 'lembar'],
            ['name' => 'Daun Jeruk Purut', 'amount' => '5', 'unit' => 'lembar'],
            ['name' => 'Bawang Merah', 'amount' => '12', 'unit' => 'siung'],
            ['name' => 'Bawang Putih', 'amount' => '6', 'unit' => 'siung'],
            ['name' => 'Cabai Merah Keriting', 'amount' => '150', 'unit' => 'gram'],
        ]);

        $recipe1->steps()->createMany([
            ['step_number' => 1, 'description' => 'Haluskan bawang merah, bawang putih, cabai merah, jahe, lengkuas, dan kunyit.'],
            ['step_number' => 2, 'description' => 'Rebus santan kental bersama bumbu halus, daun kunyit, daun jeruk, dan serai hingga mengeluarkan minyak.'],
            ['step_number' => 3, 'description' => 'Masukkan potongan daging sapi, masak dengan api kecil sambil terus diaduk perlahan hingga kuah mengering and berwarna cokelat pekat.'],
        ]);

        $recipe1->tags()->sync(
            Tag::whereIn('slug', ['rendang', 'daging', 'pedas', 'tradisional'])->pluck('id')
        );

        // 2. Nasi Goreng Kampung
        $recipe2 = Recipe::create([
            'user_id' => $user?->id,
            'category_id' => $category?->id,
            'title' => 'Nasi Goreng Kampung Spesial',
            'slug' => 'nasi-goreng-kampung-spesial',
            'description' => 'Nasi goreng kampung dengan bumbu ulek tradisional dan aroma terasi yang harum menggugah selera.',
            'content' => '<p>Resep nasi goreng sederhana ala kampung halaman dengan rasa autentik gurih pedas manis yang pas.</p>',
            'difficulty' => 'mudah',
            'prep_time' => 10,
            'cook_time' => 10,
            'servings' => 2,
            'calories' => 380,
            'protein' => 12,
            'fat' => 15,
            'carbs' => 45,
            'fiber' => 1,
            'sugar' => 2,
            'is_featured' => '0',
            'enable_comments' => '1',
            'enable_ratings' => '1',
            'status' => 'published',
            'meta_title' => 'Resep Nasi Goreng Kampung Spesial ala Rumahan',
            'meta_description' => 'Cara membuat nasi goreng kampung bumbu ulek terasi spesial yang sangat lezat dan praktis.',
            'views' => 9420,
            'rating' => 4.70,
        ]);

        $recipe2->videos()->createMany([
            ['video_url' => 'https://www.instagram.com/reel/C8aBcd1/', 'video_provider' => 'instagram', 'orders' => 1],
        ]);

        $recipe2->ingredients()->createMany([
            ['name' => 'Nasi Putih Dingin', 'amount' => '2', 'unit' => 'piring'],
            ['name' => 'Telur Ayam', 'amount' => '2', 'unit' => 'butir'],
            ['name' => 'Bawang Merah', 'amount' => '5', 'unit' => 'siung'],
            ['name' => 'Bawang Putih', 'amount' => '3', 'unit' => 'siung'],
            ['name' => 'Cabai Rawit Merah', 'amount' => '4', 'unit' => 'buah'],
            ['name' => 'Terasi Bakar', 'amount' => '1', 'unit' => 'sdt'],
            ['name' => 'Kecap Manis', 'amount' => '2', 'unit' => 'sdm'],
        ]);

        $recipe2->steps()->createMany([
            ['step_number' => 1, 'description' => 'Ulek kasar bawang merah, bawang putih, cabai rawit, terasi, dan sedikit garam.'],
            ['step_number' => 2, 'description' => 'Tumis bumbu halus hingga harum, sisihkan di pinggir wajan lalu masukkan telur dan orak-arik.'],
            ['step_number' => 3, 'description' => 'Masukkan nasi putih dingin, kecap manis, kaldu bubuk, aduk rata dengan api besar hingga bumbu merata.'],
        ]);

        $recipe2->tags()->sync(
            Tag::whereIn('slug', ['nasi-goreng', 'mudah-dibuat', 'tradisional'])->pluck('id')
        );

        // 3. Soto Ayam Lamongan
        $recipe3 = Recipe::create([
            'user_id' => $user?->id,
            'category_id' => $category?->id,
            'title' => 'Soto Ayam Lamongan Kuah Bening',
            'slug' => 'soto-ayam-lamongan-kuah-bening',
            'description' => 'Soto ayam khas Lamongan Jawa Timur dengan kuah kuning bening gurih lengkap dengan koya gurih kelapa.',
            'content' => '<p>Soto Lamongan terkenal dengan taburan bubuk koya gurih asin yang membuat cita rasa kuahnya menjadi kental dan sangat khas.</p>',
            'difficulty' => 'sedang',
            'prep_time' => 20,
            'cook_time' => 35,
            'servings' => 4,
            'calories' => 290,
            'protein' => 18,
            'fat' => 10,
            'carbs' => 22,
            'fiber' => 2,
            'sugar' => 1,
            'is_featured' => '0',
            'enable_comments' => '1',
            'enable_ratings' => '1',
            'status' => 'pending',
            'meta_title' => 'Resep Soto Ayam Lamongan Khas Jawa Timur dengan Koya',
            'meta_description' => 'Panduan lengkap membuat soto ayam Lamongan kuah gurih berempah dengan resep koya kelapa renyah.',
            'views' => 120,
            'rating' => 4.50,
        ]);

        $recipe3->videos()->createMany([
            ['video_url' => 'https://www.tiktok.com/@resepku/video/1234567890', 'video_provider' => 'tiktok', 'orders' => 1],
        ]);

        $recipe3->ingredients()->createMany([
            ['name' => 'Ayam Kampung (potong 4)', 'amount' => '1/2', 'unit' => 'ekor'],
            ['name' => 'Air Bersih', 'amount' => '1.5', 'unit' => 'liter'],
            ['name' => 'Serai (memarkan)', 'amount' => '2', 'unit' => 'batang'],
            ['name' => 'Daun Jeruk', 'amount' => '4', 'unit' => 'lembar'],
            ['name' => 'Bawang Merah', 'amount' => '8', 'unit' => 'siung'],
            ['name' => 'Bawang Putih', 'amount' => '5', 'unit' => 'siung'],
            ['name' => 'Kunyit Bakar', 'amount' => '2', 'unit' => 'ruas'],
            ['name' => 'Kemiri Sangrai', 'amount' => '4', 'unit' => 'butir'],
        ]);

        $recipe3->steps()->createMany([
            ['step_number' => 1, 'description' => 'Rebus ayam kampung dalam air bersama serai dan daun jeruk purut hingga empuk dengan api sedang.'],
            ['step_number' => 2, 'description' => 'Haluskan bawang merah, bawang putih, kunyit, jahe, kemiri, tumis hingga harum lalu masukkan ke dalam kuah rebusan ayam.'],
            ['step_number' => 3, 'description' => 'Angkat ayam dari kuah, tiriskan lalu goreng sebentar dan suwir-suwir dagingnya untuk penyajian.'],
        ]);

        $recipe3->tags()->sync(
            Tag::whereIn('slug', ['ayam', 'berkuah', 'tradisional'])->pluck('id')
        );
    }
}
