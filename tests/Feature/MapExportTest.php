<?php

use App\Models\Map;
use App\Models\User;

test('kml export requires authentication', function () {
    $map = Map::factory()->create();

    $this->get(route('map.kml', $map))
        ->assertRedirect('/login');
});

test('kmz export requires authentication', function () {
    $map = Map::factory()->create();

    $this->get(route('map.kmz', $map))
        ->assertRedirect('/login');
});

test('map image page denied without api key', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id, 'apiKey' => '']);

    $this->actingAs($user)
        ->get(route('map.image', $map))
        ->assertForbidden();
});
