<?php

use App\Models\User;
use App\Models\MasterData\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guest cannot access categories page', function () {
    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view categories page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('categories.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.master-data.kategori.index')
        ->assertViewHasAll(['categories', 'parentCategories', 'statistics']);
});

test('authenticated user can fetch categories list via AJAX', function () {
    $user = User::factory()->create();

    Category::create([
        'name' => 'Dessert',
        'slug' => 'dessert',
        'icon' => '🍰',
        'description' => 'Makanan penutup manis',
        'is_active' => '1',
        'orders' => 1,
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('categories.list'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'icon',
                        'description',
                        'parent_id',
                        'parent',
                        'is_active',
                        'orders',
                        'views',
                        'children_count',
                        'image_url',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ]
            ],
            'statistics' => [
                'total',
                'active',
                'inactive',
                'sub',
            ],
            'parents',
        ]);

    expect($response['success'])->toBeTrue();
    expect($response['data']['data'])->not->toBeEmpty();
});

test('guest cannot store a new category', function () {
    $this->postJson(route('categories.store'), [
        'name' => 'Minuman Segar',
        'slug' => 'minuman-segar',
    ])->assertStatus(401);
});

test('authenticated user can store a new category with valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('categories.store'), [
            'name' => 'Minuman Segar',
            'slug' => 'minuman-segar',
            'icon' => '🥤',
            'description' => 'Aneka jus dan es segar',
            'orders' => 2,
            'is_active' => '1',
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'icon',
                'description',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('categories', [
        'name' => 'Minuman Segar',
        'slug' => 'minuman-segar',
    ]);
});

test('authenticated user cannot store a new category with duplicate slug', function () {
    $user = User::factory()->create();

    Category::create([
        'name' => 'Dessert',
        'slug' => 'dessert',
        'is_active' => '1',
    ]);

    $this->actingAs($user)
        ->postJson(route('categories.store'), [
            'name' => 'Dessert Baru',
            'slug' => 'dessert', // duplicate slug
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('authenticated user can store a category with an image', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('category.jpg');

    $response = $this->actingAs($user)
        ->postJson(route('categories.store'), [
            'name' => 'Dessert',
            'slug' => 'dessert',
            'icon' => '🍰',
            'image' => $image,
        ])
        ->assertStatus(201);

    expect($response['data']['image_url'])->not->toBeNull();
});

test('authenticated user can update an existing category', function () {
    $user = User::factory()->create();
    
    $category = Category::create([
        'name' => 'Makanan Berat',
        'slug' => 'makanan-berat',
        'icon' => '🍛',
        'description' => 'Original description',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('categories.update', $category), [
            'name' => 'Makanan Berat Edit',
            'slug' => 'makanan-berat-edit',
            'icon' => '🍲',
            'description' => 'Updated description',
            'is_active' => '1',
            'orders' => 3,
        ])
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Makanan Berat Edit',
        'slug' => 'makanan-berat-edit',
        'description' => 'Updated description',
    ]);
});

test('authenticated user can toggle category active status', function () {
    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Makanan Berat',
        'slug' => 'makanan-berat',
        'icon' => '🍛',
        'is_active' => '1',
    ]);

    // First toggle: Active -> Inactive
    $response = $this->actingAs($user)
        ->patchJson(route('categories.toggle-active', $category))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('0');
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'is_active' => '0',
    ]);

    // Second toggle: Inactive -> Active
    $response = $this->actingAs($user)
        ->patchJson(route('categories.toggle-active', $category))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('1');
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'is_active' => '1',
    ]);
});

test('authenticated user can delete a category', function () {
    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Makanan Berat',
        'slug' => 'makanan-berat',
        'icon' => '🍛',
        'is_active' => '1',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('categories.destroy', $category))
        ->assertStatus(200);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
