<?php

use App\Models\User;

test('user can delete their account', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $this->actingAs($user)
        ->from(route('map.index'))
        ->delete(route('deleteaccount'), [
            'confirmation' => 'delete my account',
        ])
        ->assertRedirect('/')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $userId]);
});

test('delete account requires exact confirmation text', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('map.index'))
        ->delete(route('deleteaccount'), [
            'confirmation' => 'wrong text',
        ])
        ->assertRedirect(route('map.index'))
        ->assertSessionHasErrors('confirmation');

    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

test('user can renew their api key', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('renewapikey'))
        ->assertRedirect();
});

test('user can submit feedback', function () {
    $this->post(route('feedback'), [
        'feedback' => 'Great tool, love it!',
    ])->assertRedirect();
});

test('language can be changed', function () {
    $this->get(route('lang', 'es'))
        ->assertRedirect();
});
