<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('role and permission tables use ULIDs and custom columns', function () {
    // 1. Create a permission with custom is_active column
    $permission = Permission::create([
        'name' => 'manage-recipes',
        'guard_name' => 'web',
        'is_active' => '1',
    ]);

    // Assert permission has a 26-char ULID primary key
    expect($permission->id)->toBeString()->toHaveLength(26);
    expect($permission->is_active)->toBe('1');

    // 2. Create a role with custom columns (slug, type_role, description, is_active)
    $role = Role::create([
        'name' => 'chef',
        'guard_name' => 'web',
        'slug' => 'head-chef',
        'type_role' => 'custom',
        'description' => 'Chef role who can manage all recipes',
        'is_active' => '1',
    ]);

    // Assert role has a 26-char ULID primary key
    expect($role->id)->toBeString()->toHaveLength(26);
    expect($role->slug)->toBe('head-chef');
    expect($role->type_role)->toBe('custom');
    expect($role->description)->toBe('Chef role who can manage all recipes');
    expect($role->is_active)->toBe('1');

    // 3. Assign permission to role
    $role->givePermissionTo($permission);
    expect($role->hasPermissionTo('manage-recipes'))->toBeTrue();

    // 4. Create user and assign role
    $user = User::factory()->create();
    // Assert user has a 26-char ULID primary key
    expect($user->id)->toBeString()->toHaveLength(26);

    $user->assignRole($role);

    // 5. Verify relationship works correctly
    expect($user->hasRole('chef'))->toBeTrue();
    expect($user->hasPermissionTo('manage-recipes'))->toBeTrue();
});
