<?php

use App\Models\User;
use App\Models\MasterData\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access ingredients page', function () {
    $this->get(route('ingredients.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view ingredients page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ingredients.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.master-data.bahan.index')
        ->assertViewHasAll(['ingredients', 'statistics']);
});

test('authenticated user can fetch ingredients list via AJAX', function () {
    $user = User::factory()->create();

    Ingredient::create([
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('ingredients.list'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'emoji',
                        'name',
                        'slug',
                        'category',
                        'category_label',
                        'default_unit',
                        'description',
                        'is_active',
                        'views',
                        'created_at',
                        'updated_at',
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
                'categories',
                'inactive',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    expect($response['data']['data'])->not->toBeEmpty();
});

test('guest cannot store a new ingredient', function () {
    $this->postJson(route('ingredients.store'), [
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
    ])->assertStatus(401);
});

test('authenticated user can store a new ingredient with valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('ingredients.store'), [
            'emoji' => '🧅',
            'name' => 'Bawang Merah',
            'slug' => 'bawang-merah',
            'category' => 'bumbu',
            'default_unit' => 'siung',
            'is_active' => '1',
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'emoji',
                'name',
                'slug',
                'category',
                'default_unit',
                'is_active',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('ingredients', [
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
    ]);
});

test('authenticated user cannot store a new ingredient with duplicate slug', function () {
    $user = User::factory()->create();

    Ingredient::create([
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
    ]);

    $this->actingAs($user)
        ->postJson(route('ingredients.store'), [
            'emoji' => '🧅',
            'name' => 'Bawang Merah Baru',
            'slug' => 'bawang-merah', // duplicate
            'category' => 'bumbu',
            'default_unit' => 'siung',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('authenticated user can update an existing ingredient', function () {
    $user = User::factory()->create();

    $ingredient = Ingredient::create([
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('ingredients.update', $ingredient), [
            'emoji' => '🧄',
            'name' => 'Bawang Putih',
            'slug' => 'bawang-putih',
            'category' => 'bumbu',
            'default_unit' => 'siung',
            'is_active' => '1',
        ])
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('ingredients', [
        'id' => $ingredient->id,
        'emoji' => '🧄',
        'name' => 'Bawang Putih',
        'slug' => 'bawang-putih',
    ]);
});

test('authenticated user can toggle ingredient active status', function () {
    $user = User::factory()->create();

    $ingredient = Ingredient::create([
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
        'is_active' => '1',
    ]);

    // First toggle: Active -> Inactive
    $response = $this->actingAs($user)
        ->patchJson(route('ingredients.toggle-active', $ingredient))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('0');
    $this->assertDatabaseHas('ingredients', [
        'id' => $ingredient->id,
        'is_active' => '0',
    ]);

    // Second toggle: Inactive -> Active
    $response = $this->actingAs($user)
        ->patchJson(route('ingredients.toggle-active', $ingredient))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('1');
    $this->assertDatabaseHas('ingredients', [
        'id' => $ingredient->id,
        'is_active' => '1',
    ]);
});

test('authenticated user can delete an ingredient', function () {
    $user = User::factory()->create();

    $ingredient = Ingredient::create([
        'emoji' => '🧅',
        'name' => 'Bawang Merah',
        'slug' => 'bawang-merah',
        'category' => 'bumbu',
        'default_unit' => 'siung',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('ingredients.destroy', $ingredient))
        ->assertStatus(200);

    $this->assertDatabaseMissing('ingredients', [
        'id' => $ingredient->id,
    ]);
});
