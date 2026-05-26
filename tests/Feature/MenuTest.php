<?php

use App\Models\Konfigurasi\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('menu model uses ULID and scopeActive works', function () {
    // 1. Create an active menu
    $activeMenu = Menu::create([
        'name' => 'Dashboard',
        'url' => 'dashboard',
        'category' => 'main',
        'icon' => 'bi-speedometer2',
        'is_active' => '1',
        'orders' => 1,
    ]);

    // 2. Create an inactive menu
    $inactiveMenu = Menu::create([
        'name' => 'Settings',
        'url' => 'settings',
        'category' => 'system',
        'icon' => 'bi-gear',
        'is_active' => '0',
        'orders' => 2,
    ]);

    // Assert primary keys are ULIDs (length 26)
    expect($activeMenu->id)->toBeString()->toHaveLength(26);
    expect($inactiveMenu->id)->toBeString()->toHaveLength(26);

    // Assert scopeActive works
    $activeMenus = Menu::active()->get();
    expect($activeMenus->count())->toBe(1);
    expect($activeMenus->first()->id)->toBe($activeMenu->id);
});

test('attachMenupermission trait method links menu to permissions', function () {
    // 1. Create a menu
    $menu = Menu::create([
        'name' => 'Recipes Management',
        'url' => 'recipes',
        'category' => 'data',
        'icon' => 'bi-book',
        'is_active' => '1',
        'orders' => 10,
    ]);

    // 2. Create a test controller/class that uses the trait to call it
    $controller = new class {
        use \App\Traits\HasMenuPermission;
    };

    // Create a role to assign
    $role = Role::create([
        'name' => 'editor',
        'guard_name' => 'web',
    ]);

    // Call the trait method
    $controller->attachMenupermission($menu, ['read', 'create'], [$role->name]);

    // Assert permission is created with correct name format "action url"
    $permissionRead = Permission::where('name', 'read recipes')->first();
    expect($permissionRead)->not->toBeNull();
    expect($permissionRead->id)->toBeString()->toHaveLength(26);

    // Assert relation via pivot works
    expect($permissionRead->menus->first()->id)->toBe($menu->id);

    // Assert role assignment works
    expect($role->hasPermissionTo('read recipes'))->toBeTrue();
});
