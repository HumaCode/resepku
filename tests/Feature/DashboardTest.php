<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard page redirects guest to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('dashboard page can be rendered for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Dashboard');
    $response->assertSee($user->name);
});
