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

test('authenticated user can view matrix table via AJAX', function () {
    $user = User::factory()->create();

    // Create a custom role to verify it gets rendered
    Role::create([
        'name' => 'editor',
        'guard_name' => 'web',
        'slug' => 'editor',
        'type_role' => 'custom',
        'description' => 'Editor role description',
        'is_active' => '1',
    ]);

    $this->actingAs($user)
        ->get(route('roles-permissions.index'), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertSuccessful()
        ->assertViewIs('pages.pengguna.role-permission.partials.matrix-table')
        ->assertSee('editor');
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

test('guest cannot update a role', function () {
    $role = Role::create([
        'name' => 'editor',
        'slug' => 'editor',
        'guard_name' => 'web',
    ]);

    $this->putJson(route('roles-permissions.update', $role), [
        'name' => 'editor-new',
        'slug' => 'editor-new',
    ])->assertStatus(401);
});

test('authenticated user can update a custom role', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'Editor Konten',
        'slug' => 'editor-konten',
        'guard_name' => 'web',
        'color' => '#22c55e',
        'icon' => '📝',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('roles-permissions.update', $role), [
            'name' => 'Editor Diperbarui',
            'slug' => 'editor-diperbarui',
            'description' => 'Deskripsi baru',
            'color' => '#3b82f6',
            'icon' => '🛡️',
        ])
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    expect($response['data']['name'])->toBe('Editor Diperbarui');
    expect($response['data']['slug'])->toBe('editor-diperbarui');
    expect($response['data']['color'])->toBe('#3b82f6');
    expect($response['data']['icon'])->toBe('🛡️');

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'Editor Diperbarui',
        'slug' => 'editor-diperbarui',
        'color' => '#3b82f6',
        'icon' => '🛡️',
    ]);
});

test('updating a role rejects duplicate names and slugs', function () {
    $user = User::factory()->create();
    
    $role1 = Role::create([
        'name' => 'editor',
        'slug' => 'editor',
        'guard_name' => 'web',
    ]);
    
    $role2 = Role::create([
        'name' => 'author',
        'slug' => 'author',
        'guard_name' => 'web',
    ]);

    $this->actingAs($user)
        ->putJson(route('roles-permissions.update', $role2), [
            'name' => 'editor',
            'slug' => 'editor',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'slug']);
});

test('updating system role slug is blocked', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'admin',
        'slug' => 'admin',
        'guard_name' => 'web',
    ]);

    $this->actingAs($user)
        ->putJson(route('roles-permissions.update', $role), [
            'name' => 'Admin Baru',
            'slug' => 'admin-baru', // changing slug of system role
        ])
        ->assertStatus(422)
        ->assertJsonFragment([
            'success' => false,
            'message' => 'Role bawaan sistem tidak boleh diubah slug-nya.'
        ]);
});

test('guest cannot delete a role', function () {
    $role = Role::create([
        'name' => 'editor',
        'slug' => 'editor',
        'guard_name' => 'web',
    ]);

    $this->deleteJson(route('roles-permissions.destroy', $role))
        ->assertStatus(401);
});

test('authenticated user can delete a custom role', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'Editor Baru',
        'slug' => 'editor-baru',
        'guard_name' => 'web',
    ]);

    $response = $this->actingAs($user)
        ->deleteJson(route('roles-permissions.destroy', $role))
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    expect($response['message'])->toBe('Role berhasil dihapus');

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

test('authenticated user cannot delete a system role', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'admin',
        'slug' => 'admin',
        'guard_name' => 'web',
    ]);

    $response = $this->actingAs($user)
        ->deleteJson(route('roles-permissions.destroy', $role))
        ->assertStatus(422);

    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Role bawaan sistem tidak dapat dihapus.');

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
    ]);
});

test('guest cannot sync permissions', function () {
    $this->postJson(route('roles-permissions.sync'), [
        'matrix' => [
            'admin' => ['resep.view-all']
        ]
    ])->assertStatus(401);
});

test('authenticated user can sync permissions with valid matrix payload', function () {
    $user = User::factory()->create();
    
    $adminRole = Role::create([
        'name' => 'admin',
        'slug' => 'admin',
        'guard_name' => 'web',
    ]);
    
    $userRole = Role::create([
        'name' => 'user',
        'slug' => 'user',
        'guard_name' => 'web',
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('roles-permissions.sync'), [
            'matrix' => [
                'admin' => ['resep.view-all', 'resep.create'],
                'user' => ['resep.view-all']
            ]
        ])
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    expect($response['message'])->toBe('Perubahan izin berhasil disimpan!');

    // Assert database sync
    expect($adminRole->fresh()->hasPermissionTo('resep.view-all'))->toBeTrue();
    expect($adminRole->fresh()->hasPermissionTo('resep.create'))->toBeTrue();
    expect($userRole->fresh()->hasPermissionTo('resep.view-all'))->toBeTrue();
    expect($userRole->fresh()->hasPermissionTo('resep.create'))->toBeFalse();
});

test('authenticated user can toggle active status of a custom role', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'Editor Baru',
        'slug' => 'editor-baru',
        'guard_name' => 'web',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->patchJson(route('roles-permissions.toggle-active', $role))
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    expect($response['data']['is_active'])->toBe('0');
    expect($role->fresh()->is_active)->toBe('0');

    $response = $this->actingAs($user)
        ->patchJson(route('roles-permissions.toggle-active', $role))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('1');
    expect($role->fresh()->is_active)->toBe('1');
});

test('authenticated user cannot toggle active status of a system role', function () {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'admin',
        'slug' => 'admin',
        'guard_name' => 'web',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->patchJson(route('roles-permissions.toggle-active', $role))
        ->assertStatus(422);

    expect($response['success'])->toBeFalse();
    expect($role->fresh()->is_active)->toBe('1');
});



