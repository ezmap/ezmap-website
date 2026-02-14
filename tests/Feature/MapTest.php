<?php

use App\Models\Map;
use App\Models\User;

test('guests cannot access map creation', function () {
    $this->get('/map/create')
        ->assertRedirect('/login');
});

test('authenticated user can access map creation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/map/create')
        ->assertOk();
});

test('authenticated user can create a map', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/map', [
            'title' => 'My Test Map',
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'responsiveMap' => true,
            'latitude' => 51.5074,
            'longitude' => -0.1278,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => json_encode(['zoom' => 10, 'mapTypeId' => 'roadmap']),
        ])->assertRedirect();

    $this->assertDatabaseHas('maps', [
        'user_id' => $user->id,
        'title' => 'My Test Map',
    ]);
});

test('embeddable map returns javascript content type', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create([
        'user_id' => $user->id,
        'embeddable' => true,
    ]);

    $response = $this->get(route('map.show', $map));
    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('text/javascript');
});

test('non-embeddable map returns forbidden', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id, 'embeddable' => false]);

    $this->actingAs($user)
        ->get(route('map.show', $map))
        ->assertForbidden();
});

test('authenticated user can edit their map', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('map.edit', $map))
        ->assertOk();
});

test('authenticated user can update their map', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => 'Updated Title',
            'mapContainer' => 'map',
            'width' => 800,
            'height' => 600,
            'responsiveMap' => false,
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => json_encode(['zoom' => 12, 'mapTypeId' => 'satellite']),
        ])->assertRedirect();

    $this->assertDatabaseHas('maps', [
        'id' => $map->id,
        'title' => 'Updated Title',
        'width' => 800,
    ]);
});

test('authenticated user can delete their map', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('map.destroy', $map))
        ->assertRedirect();

    $this->assertSoftDeleted('maps', ['id' => $map->id]);
});

test('authenticated user can restore a deleted map', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);
    $map->delete();

    $this->actingAs($user)
        ->post(route('map.undelete', $map->id))
        ->assertRedirect();

    $this->assertDatabaseHas('maps', [
        'id' => $map->id,
        'deleted_at' => null,
    ]);
});

test('user cannot edit another users map', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $other->id]);

    $this->actingAs($user)
        ->get(route('map.edit', $map))
        ->assertForbidden();
});

test('maps dashboard shows only users maps', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $myMap = Map::factory()->create(['user_id' => $user->id, 'title' => 'My Map']);
    $otherMap = Map::factory()->create(['user_id' => $other->id, 'title' => 'Not My Map']);

    $this->actingAs($user)
        ->get(route('map.index'))
        ->assertOk()
        ->assertSee('My Map')
        ->assertDontSee('Not My Map');
});
