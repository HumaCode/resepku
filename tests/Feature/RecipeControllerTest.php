<?php

use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\MasterData\Tag;
use App\Models\Konten\Recipe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::create([
        'name' => 'Makanan Berat',
        'slug' => 'makanan-berat',
        'is_active' => '1',
    ]);
    $this->tag = Tag::create([
        'name' => 'Pedas',
        'slug' => 'pedas',
        'is_active' => '1',
    ]);
});

test('guest cannot access recipes pages', function () {
    $this->get(route('recipes.index'))->assertRedirect(route('login'));
    $this->get(route('recipes.create'))->assertRedirect(route('login'));
    $this->post(route('recipes.store'), [])->assertRedirect(route('login'));
});

test('user can access recipes create page', function () {
    $this->actingAs($this->user)
        ->get(route('recipes.create'))
        ->assertOk()
        ->assertViewIs('pages.konten.resep.create')
        ->assertViewHasAll(['categories', 'tags']);
});

test('user can store a new recipe with all relations', function () {
    Storage::fake('public');
    
    $payload = [
        'category_id' => $this->category->id,
        'title' => 'Nasi Goreng Spesial',
        'slug' => 'nasi-goreng-spesial',
        'description' => 'Nasi goreng lezat dengan telur dan ayam.',
        'content' => '<p>Langkah detail cara memasak nasi goreng...</p>',
        'difficulty' => 'mudah',
        'prep_time' => 10,
        'cook_time' => 15,
        'servings' => 2,
        'calories' => 350,
        'protein' => 12,
        'fat' => 10,
        'carbs' => 45,
        'fiber' => 2,
        'sugar' => 1,
        'is_featured' => '1',
        'enable_comments' => '1',
        'enable_ratings' => '1',
        'status' => 'published',
        'meta_title' => 'Resep Nasi Goreng Spesial',
        'meta_description' => 'Resep nasi goreng spesial paling enak.',
        'cover' => UploadedFile::fake()->image('nasigoreng.jpg'),
        'tags' => [$this->tag->id],
        'ingredients' => [
            ['name' => 'Nasi Putih', 'amount' => '2', 'unit' => 'piring'],
            ['name' => 'Bawang Putih', 'amount' => '3', 'unit' => 'siung'],
        ],
        'steps' => [
            ['step_number' => 1, 'description' => 'Haluskan bawang putih dan bawang merah.'],
            ['step_number' => 2, 'description' => 'Tumis bumbu halus hingga harum.'],
        ],
        'videos' => [
            ['video_provider' => 'youtube', 'video_url' => 'https://www.youtube.com/watch?v=123'],
            ['video_provider' => 'tiktok', 'video_url' => 'https://www.tiktok.com/@user/video/456'],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('recipes.store'), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil dipublikasikan!',
            'redirect' => route('recipes.index'),
        ]);

    $this->assertDatabaseHas('recipes', [
        'title' => 'Nasi Goreng Spesial',
        'slug' => 'nasi-goreng-spesial',
        'prep_time' => 10,
        'cook_time' => 15,
    ]);

    $recipe = Recipe::where('slug', 'nasi-goreng-spesial')->first();
    expect($recipe)->not->toBeNull();
    
    // Check media collection
    expect($recipe->getFirstMediaUrl('cover'))->not->toBeEmpty();
    
    // Check relations count
    expect($recipe->ingredients)->toHaveCount(2);
    expect($recipe->steps)->toHaveCount(2);
    expect($recipe->videos)->toHaveCount(2);
    expect($recipe->tags)->toHaveCount(1);
});

test('user can access recipes index page', function () {
    $this->actingAs($this->user)
        ->get(route('recipes.index'))
        ->assertOk()
        ->assertViewIs('pages.konten.resep.index')
        ->assertViewHasAll(['categories', 'stats']);
});

test('user can fetch paginated recipes via ajax', function () {
    for ($i = 0; $i < 15; $i++) {
        Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => "Recipe Test {$i}",
            'slug' => "recipe-test-{$i}",
            'description' => 'Test description',
            'content' => 'Test content',
            'difficulty' => 'mudah',
            'prep_time' => 10,
            'cook_time' => 10,
            'servings' => 2,
            'status' => 'published',
        ]);
    }

    $response = $this->actingAs($this->user)
        ->getJson(route('recipes.index'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'status',
                    'difficulty',
                    'prep_time',
                    'cook_time',
                    'servings',
                    'rating',
                    'views',
                    'is_featured',
                    'cover_url',
                    'category' => ['name', 'slug'],
                    'author' => ['name'],
                    'tags',
                ]
            ],
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
                'from',
                'to',
            ]
        ])
        ->assertJsonCount(10, 'data'); // Default pagination per_page is 10
});

test('user can filter recipes via ajax', function () {
    // Create matching recipe
    $match = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Sop Buntut Spesial',
        'slug' => 'sop-buntut-spesial',
        'description' => 'Sop buntut enak.',
        'content' => 'Langkah...',
        'difficulty' => 'sedang',
        'prep_time' => 15,
        'cook_time' => 45,
        'servings' => 4,
        'status' => 'published',
    ]);

    // Create non-matching recipe
    Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Bakso Sapi Solo',
        'slug' => 'bakso-sapi-solo',
        'description' => 'Bakso enak.',
        'content' => 'Langkah...',
        'difficulty' => 'sedang',
        'prep_time' => 15,
        'cook_time' => 45,
        'servings' => 4,
        'status' => 'draft',
    ]);

    // Search filter
    $response = $this->actingAs($this->user)
        ->getJson(route('recipes.index', ['search' => 'Sop']));
    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $match->id);

    // Status filter
    $response = $this->actingAs($this->user)
        ->getJson(route('recipes.index', ['status' => 'draft']));
    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'bakso-sapi-solo');
});

test('user can approve a pending recipe', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Sate Kambing Madura',
        'slug' => 'sate-kambing-madura',
        'description' => 'Sate enak.',
        'content' => 'Langkah...',
        'difficulty' => 'expert',
        'prep_time' => 20,
        'cook_time' => 20,
        'servings' => 4,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->patchJson(route('recipes.approve', $recipe->id));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil disetujui dan dipublikasikan!',
        ]);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'status' => 'published',
    ]);
});

test('user can delete a recipe', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Mie Goreng Aceh',
        'slug' => 'mie-goreng-aceh',
        'description' => 'Mie enak.',
        'content' => 'Langkah...',
        'difficulty' => 'mudah',
        'prep_time' => 10,
        'cook_time' => 10,
        'servings' => 2,
        'status' => 'draft',
    ]);

    $response = $this->actingAs($this->user)
        ->deleteJson(route('recipes.destroy', $recipe->id));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil dihapus!',
        ]);

    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id,
    ]);
});

test('user can view recipe detail page', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Nasi Goreng Kambing',
        'slug' => 'nasi-goreng-kambing',
        'description' => 'Nasi goreng kambing enak.',
        'content' => '<p>Langkah detail...</p>',
        'difficulty' => 'sedang',
        'prep_time' => 15,
        'cook_time' => 15,
        'servings' => 2,
        'status' => 'published',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('recipes.show', $recipe->id));

    $response->assertSuccessful()
        ->assertSee('Nasi Goreng Kambing')
        ->assertSee('Nasi goreng kambing enak.')
        ->assertSee('Detail Resep');
});

test('user can toggle recipe status', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Ayam Penyet Cabai Ijo',
        'slug' => 'ayam-penyet-cabai-ijo',
        'description' => 'Ayam penyet enak.',
        'content' => 'Langkah...',
        'difficulty' => 'mudah',
        'prep_time' => 15,
        'cook_time' => 15,
        'servings' => 2,
        'status' => 'published',
    ]);

    $response = $this->actingAs($this->user)
        ->patchJson(route('recipes.toggle-status', $recipe->id));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Status resep berhasil diubah!',
            'status' => 'draft',
        ]);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'status' => 'draft',
    ]);
});

test('user can toggle recipe featured status', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Es Campur Spesial',
        'slug' => 'es-campur-spesial',
        'description' => 'Es campur.',
        'content' => 'Langkah...',
        'difficulty' => 'mudah',
        'prep_time' => 5,
        'cook_time' => 5,
        'servings' => 3,
        'status' => 'published',
        'is_featured' => '0',
    ]);

    $response = $this->actingAs($this->user)
        ->patchJson(route('recipes.toggle-featured', $recipe->id));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil ditandai sebagai unggulan!',
            'is_featured' => '1',
        ]);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'is_featured' => '1',
    ]);
});

test('user can duplicate a recipe', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Martabak Manis Cokelat',
        'slug' => 'martabak-manis-cokelat',
        'description' => 'Martabak manis.',
        'content' => 'Langkah...',
        'difficulty' => 'sedang',
        'prep_time' => 20,
        'cook_time' => 15,
        'servings' => 4,
        'status' => 'published',
    ]);

    $response = $this->actingAs($this->user)
        ->postJson(route('recipes.duplicate', $recipe->id));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil diduplikat sebagai draft!',
            'redirect' => route('recipes.index'),
        ]);

    $this->assertDatabaseHas('recipes', [
        'title' => 'Martabak Manis Cokelat (Duplikat)',
        'status' => 'draft',
    ]);
});

test('user can access recipes edit page', function () {
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Sop Buntut Sapi',
        'slug' => 'sop-buntut-sapi',
        'description' => 'Sop buntut.',
        'content' => 'Langkah...',
        'difficulty' => 'sulit',
        'prep_time' => 30,
        'cook_time' => 90,
        'servings' => 4,
        'status' => 'published',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('recipes.edit', $recipe->id));

    $response->assertOk()
        ->assertViewIs('pages.konten.resep.edit')
        ->assertViewHasAll(['recipe', 'categories', 'tags', 'masterIngredients']);
});

test('user can update recipe with all relations', function () {
    Storage::fake('public');
    
    $recipe = Recipe::create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'title' => 'Sop Buntut Sapi',
        'slug' => 'sop-buntut-sapi',
        'description' => 'Sop buntut.',
        'content' => 'Langkah...',
        'difficulty' => 'sulit',
        'prep_time' => 30,
        'cook_time' => 90,
        'servings' => 4,
        'status' => 'published',
    ]);

    $payload = [
        'category_id' => $this->category->id,
        'title' => 'Sop Buntut Sapi Premium',
        'slug' => 'sop-buntut-sapi-premium',
        'description' => 'Sop buntut sapi istimewa.',
        'content' => '<p>Langkah detail cara memasak sop buntut...</p>',
        'difficulty' => 'expert',
        'prep_time' => 40,
        'cook_time' => 100,
        'servings' => 5,
        'calories' => 500,
        'protein' => 25,
        'fat' => 20,
        'carbs' => 60,
        'fiber' => 5,
        'sugar' => 3,
        'is_featured' => '1',
        'enable_comments' => '1',
        'enable_ratings' => '1',
        'status' => 'published',
        'meta_title' => 'Resep Sop Buntut Sapi Premium',
        'meta_description' => 'Resep sop buntut sapi premium paling enak.',
        'cover' => UploadedFile::fake()->image('sopbuntut.jpg'),
        'tags' => [$this->tag->id],
        'ingredients' => [
            ['name' => 'Buntut Sapi', 'amount' => '1', 'unit' => 'kg'],
            ['name' => 'Wortel', 'amount' => '3', 'unit' => 'buah'],
        ],
        'steps' => [
            ['step_number' => 1, 'description' => 'Rebus buntut sapi.'],
            ['step_number' => 2, 'description' => 'Tambahkan wortel dan kentang.'],
        ],
        'videos' => [
            ['video_provider' => 'youtube', 'video_url' => 'https://www.youtube.com/watch?v=999'],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->putJson(route('recipes.update', $recipe->id), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Resep berhasil diperbarui!',
            'redirect' => route('recipes.index'),
        ]);

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'title' => 'Sop Buntut Sapi Premium',
        'slug' => 'sop-buntut-sapi-premium',
        'prep_time' => 40,
        'cook_time' => 100,
    ]);

    $updatedRecipe = Recipe::findOrFail($recipe->id);
    
    // Check media collection
    expect($updatedRecipe->getFirstMediaUrl('cover'))->not->toBeEmpty();
    
    // Check relations count
    expect($updatedRecipe->ingredients)->toHaveCount(2);
    expect($updatedRecipe->steps)->toHaveCount(2);
    expect($updatedRecipe->videos)->toHaveCount(1);
    expect($updatedRecipe->tags)->toHaveCount(1);
});

test('user can store a new recipe pasting embed script in video_url and it gets sanitized to raw url', function () {
    Storage::fake('public');
    
    $payload = [
        'category_id' => $this->category->id,
        'title' => 'Nasi Goreng Spesial Embed',
        'slug' => 'nasi-goreng-spesial-embed',
        'description' => 'Nasi goreng lezat.',
        'content' => '<p>Langkah detail cara memasak...</p>',
        'difficulty' => 'mudah',
        'prep_time' => 10,
        'cook_time' => 15,
        'servings' => 2,
        'status' => 'published',
        'videos' => [
            [
                'video_provider' => 'tiktok', 
                'video_url' => '<blockquote class="tiktok-embed" cite="https://www.tiktok.com/@ramlan.vlg/video/7636418864703720711" data-video-id="7636418864703720711"><section></section></blockquote>'
            ],
            [
                'video_provider' => 'instagram',
                'video_url' => '<blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/reel/DY8ZEKoDSLL/" data-instgrm-version="14"></blockquote>'
            ]
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('recipes.store'), $payload);

    $response->assertOk();

    $this->assertDatabaseHas('recipes', [
        'title' => 'Nasi Goreng Spesial Embed',
    ]);

    $recipe = Recipe::where('slug', 'nasi-goreng-spesial-embed')->first();
    expect($recipe)->not->toBeNull();
    
    // Check videos URLs are cleaned
    expect($recipe->videos)->toHaveCount(2);
    
    $tiktokVideo = $recipe->videos->where('video_provider', 'tiktok')->first();
    expect($tiktokVideo->video_url)->toBe('https://www.tiktok.com/@ramlan.vlg/video/7636418864703720711');

    $instaVideo = $recipe->videos->where('video_provider', 'instagram')->first();
    expect($instaVideo->video_url)->toBe('https://www.instagram.com/reel/DY8ZEKoDSLL/');
});


