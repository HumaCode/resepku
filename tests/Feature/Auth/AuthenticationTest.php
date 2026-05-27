<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'username' => $user->username,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('inactive users cannot authenticate', function () {
    $user = User::factory()->create([
        'is_active' => '0',
    ]);

    $response = $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('username');
});

test('inactive user accessing dashboard is logged out and redirected to login with toast error', function () {
    $user = User::factory()->create([
        'is_active' => '0',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('toast_error');
});
