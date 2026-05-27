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
