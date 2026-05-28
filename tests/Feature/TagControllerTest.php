<?php

use App\Models\User;
use App\Models\MasterData\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access tags page', function () {
    $this->get(route('tags.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user can view tags page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('tags.index'))
        ->assertSuccessful()
        ->assertViewIs('pages.master-data.tag.index')
        ->assertViewHasAll(['tags', 'statistics']);
});

test('authenticated user can fetch tags list via AJAX', function () {
    $user = User::factory()->create();

    Tag::create([
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
        'is_hot' => '1',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('tags.list'))
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
                        'color',
                        'is_hot',
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
                'hot',
                'new',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    expect($response['data']['data'])->not->toBeEmpty();
});

test('guest cannot store a new tag', function () {
    $this->postJson(route('tags.store'), [
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
    ])->assertStatus(401);
});

test('authenticated user can store a new tag with valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('tags.store'), [
            'name' => 'rendang',
            'slug' => 'rendang',
            'color' => '#ef4444',
            'is_hot' => '1',
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
                'color',
                'is_hot',
                'is_active',
            ]
        ]);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('tags', [
        'name' => 'rendang',
        'slug' => 'rendang',
    ]);
});

test('authenticated user cannot store a new tag with duplicate slug', function () {
    $user = User::factory()->create();

    Tag::create([
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
    ]);

    $this->actingAs($user)
        ->postJson(route('tags.store'), [
            'name' => 'Rendang Baru',
            'slug' => 'rendang', // duplicate slug
            'color' => '#ef4444',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('authenticated user can update an existing tag', function () {
    $user = User::factory()->create();
    
    $tag = Tag::create([
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
        'is_hot' => '1',
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('tags.update', $tag), [
            'name' => 'rendang pedas',
            'slug' => 'rendang-pedas',
            'color' => '#dc2626',
            'is_hot' => '1',
            'is_active' => '1',
        ])
        ->assertStatus(200);

    expect($response['success'])->toBeTrue();
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'rendang pedas',
        'slug' => 'rendang-pedas',
        'color' => '#dc2626',
    ]);
});

test('authenticated user can toggle tag active status', function () {
    $user = User::factory()->create();

    $tag = Tag::create([
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
        'is_active' => '1',
    ]);

    // First toggle: Active -> Inactive
    $response = $this->actingAs($user)
        ->patchJson(route('tags.toggle-active', $tag))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('0');
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'is_active' => '0',
    ]);

    // Second toggle: Inactive -> Active
    $response = $this->actingAs($user)
        ->patchJson(route('tags.toggle-active', $tag))
        ->assertStatus(200);

    expect($response['data']['is_active'])->toBe('1');
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'is_active' => '1',
    ]);
});

test('authenticated user can delete a tag', function () {
    $user = User::factory()->create();

    $tag = Tag::create([
        'name' => 'rendang',
        'slug' => 'rendang',
        'color' => '#ef4444',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('tags.destroy', $tag))
        ->assertStatus(200);

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});
