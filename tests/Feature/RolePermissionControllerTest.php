<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access roles and permissions page', function () {
    $this->get(route('roles-permissions.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view roles and permissions page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('roles-permissions.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.pengguna.role-permission.index');
});

test('authenticated user can fetch roles data via AJAX', function () {
    $user = User::factory()->create();

    // Create a custom role to verify fetching
    Role::create([
        'name' => 'editor',
        'guard_name' => 'web',
        'slug' => 'editor',
        'type_role' => 'custom',
        'description' => 'Editor role description',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('roles-permissions.roles'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'type_role',
                    'description',
                    'is_active',
                    'users_count',
                    'permissions_count',
                ]
            ]
        ]);

    expect($response['success'])->toBeTrue();
    expect($response['data'])->not->toBeEmpty();
});

test('guest cannot store a new role', function () {
    $this->postJson(route('roles-permissions.store'), [
        'name' => 'editor',
        'slug' => 'editor',
    ])->assertStatus(401);
});

test('authenticated user can store a new role with valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('roles-permissions.store'), [
            'name' => 'Editor Konten',
            'slug' => 'editor-konten',
            'description' => 'Mengelola konten resep',
            'color' => '#22c55e',
            'icon' => '📝',
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'type_role',
                'description',
                'is_active',
                'color',
                'icon',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    expect($response['data']['name'])->toBe('Editor Konten');
    expect($response['data']['slug'])->toBe('editor-konten');
    expect($response['data']['color'])->toBe('#22c55e');
    expect($response['data']['icon'])->toBe('📝');

    $this->assertDatabaseHas('roles', [
        'name' => 'Editor Konten',
        'slug' => 'editor-konten',
        'color' => '#22c55e',
        'icon' => '📝',
    ]);
});

test('storing a role validates required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('roles-permissions.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'slug']);
});

test('storing a role rejects duplicate names and slugs', function () {
    $user = User::factory()->create();

    Role::create([
        'name' => 'editor',
        'slug' => 'editor',
        'guard_name' => 'web',
    ]);

    $this->actingAs($user)
        ->postJson(route('roles-permissions.store'), [
            'name' => 'editor',
            'slug' => 'editor',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'slug']);
});

