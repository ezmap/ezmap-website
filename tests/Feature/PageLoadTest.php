<?php

use App\Models\User;

test('homepage loads for guests', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('EZ Map');
});

test('homepage redirects authenticated users to home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect('/home');
});

test('help page loads', function () {
    $this->get('/help')
        ->assertOk();
});

test('feedback page loads', function () {
    $this->get('/feedback')
        ->assertOk();
});

test('api docs page loads', function () {
    $this->get('/api')
        ->assertOk();
});
