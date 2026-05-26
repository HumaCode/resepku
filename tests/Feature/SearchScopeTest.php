<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Media;
use App\Models\Konfigurasi\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('User scopeSearch filters records correctly', function () {
    $user1 = User::factory()->create([
        'name' => 'Budi Santoso',
        'username' => 'budis',
        'email' => 'budi@resepku.com',
        'telp' => '081234567890',
    ]);

    $user2 = User::factory()->create([
        'name' => 'Siti Aminah',
        'username' => 'sitia',
        'email' => 'siti@resepku.com',
        'telp' => '089876543210',
    ]);

    // Search by name
    $results = User::search('Budi')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($user1->id);

    // Search by username
    $results = User::search('sitia')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($user2->id);

    // Search by phone
    $results = User::search('567890')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($user1->id);
});

test('Role scopeSearch filters records correctly', function () {
    $role1 = Role::create([
        'name' => 'Super Administrator',
        'slug' => 'super-admin',
        'description' => 'A role with all system configurations access',
        'guard_name' => 'web',
    ]);

    $role2 = Role::create([
        'name' => 'Content Editor',
        'slug' => 'editor',
        'description' => 'A role to manage recipes and tags',
        'guard_name' => 'web',
    ]);

    // Search by name
    $results = Role::search('Content')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($role2->id);

    // Search by description
    $results = Role::search('configurations')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($role1->id);
});

test('Permission scopeSearch filters records correctly', function () {
    $perm1 = Permission::create([
        'name' => 'create recipes',
        'guard_name' => 'web',
    ]);

    $perm2 = Permission::create([
        'name' => 'delete users',
        'guard_name' => 'api',
    ]);

    // Search by name
    $results = Permission::search('recipes')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($perm1->id);

    // Search by guard name
    $results = Permission::search('api')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($perm2->id);
});

test('Menu scopeSearch filters records correctly', function () {
    $menu1 = Menu::create([
        'name' => 'Daftar Resep',
        'url' => 'recipes-list',
        'category' => 'KONTEN',
    ]);

    $menu2 = Menu::create([
        'name' => 'User Management',
        'url' => 'users',
        'category' => 'PENGGUNA',
    ]);

    // Search by name
    $results = Menu::search('Resep')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($menu1->id);

    // Search by category
    $results = Menu::search('PENGGUNA')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($menu2->id);
});

test('Media scopeSearch filters records correctly', function () {
    $user = User::factory()->create();
    
    // Create media instances linked to user
    $media1 = new Media();
    $media1->name = 'Avatar Image';
    $media1->file_name = 'avatar.jpg';
    $media1->mime_type = 'image/jpeg';
    $media1->disk = 'public';
    $media1->collection_name = 'avatars';
    $media1->model_type = User::class;
    $media1->model_id = $user->id;
    $media1->size = 100;
    $media1->manipulations = [];
    $media1->custom_properties = [];
    $media1->generated_conversions = [];
    $media1->responsive_images = [];
    $media1->save();

    $media2 = new Media();
    $media2->name = 'Recipe Thumbnail';
    $media2->file_name = 'thumbnail.png';
    $media2->mime_type = 'image/png';
    $media2->disk = 'public';
    $media2->collection_name = 'recipes';
    $media2->model_type = User::class;
    $media2->model_id = $user->id;
    $media2->size = 200;
    $media2->manipulations = [];
    $media2->custom_properties = [];
    $media2->generated_conversions = [];
    $media2->responsive_images = [];
    $media2->save();

    // Search by file_name
    $results = Media::search('thumbnail')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($media2->id);

    // Search by collection_name
    $results = Media::search('avatars')->get();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($media1->id);
});
