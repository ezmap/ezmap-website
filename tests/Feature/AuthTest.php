<?php

use App\Models\User;

test('login page loads', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Login');
});

test('register page loads', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Register');
});

test('password reset page loads', function () {
    $this->get('/password/reset')
        ->assertOk();
});

test('user can register', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect('/home');

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])->assertRedirect('/home');

    $this->assertAuthenticatedAs($user);
});

test('user cannot login with wrong password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});

test('guests cannot access home page', function () {
    $this->get('/home')
        ->assertRedirect('/login');
});

test('authenticated user can access home page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/home')
        ->assertRedirect(route('map.index'));
});
