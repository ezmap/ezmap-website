<?php

use App\Models\Map;
use App\Models\User;
use App\Models\Theme;
use App\Services\MapCodeGenerator;

test('generated code contains map initialization', function () {
    $map = Map::factory()->create([
        'latitude' => '51.5074',
        'longitude' => '-0.1278',
        'mapContainer' => 'my-map',
    ]);

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain("function init{$map->id}()")
        ->toContain("document.getElementById('my-map')")
        ->toContain('new google.maps.Map(mapElement, mapOptions)')
        ->toContain("window.addEventListener('load', init{$map->id})");
});

test('generated code includes correct center coordinates', function () {
    $map = Map::factory()->create([
        'latitude' => '40.7128',
        'longitude' => '-74.006',
    ]);

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('"lat": 40.7128')
        ->toContain('"lng": -74.006');
});

test('generated code includes control positions as enum references', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    // Save map with control positions set
    $map->mapOptions = array_merge(
        (array) $map->mapOptions,
        [
            'fullscreenControlPosition' => 'TOP_LEFT',
            'zoomControlPosition' => 'BOTTOM_RIGHT',
        ]
    );
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('google.maps.ControlPosition.TOP_LEFT')
        ->toContain('google.maps.ControlPosition.BOTTOM_RIGHT')
        ->not->toContain('"TOP_LEFT"')
        ->not->toContain('"BOTTOM_RIGHT"');
});

test('generated code includes data layers when enabled', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $map->mapOptions = array_merge(
        (array) $map->mapOptions,
        [
            'trafficLayer' => 'true',
            'transitLayer' => 'true',
            'bicyclingLayer' => 'false',
        ]
    );
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('new google.maps.TrafficLayer().setMap(map)')
        ->toContain('new google.maps.TransitLayer().setMap(map)')
        ->not->toContain('BicyclingLayer');
});

test('generated code includes google map id and color scheme', function () {
    $map = Map::factory()->create([
        'google_map_id' => 'abc123mapid',
    ]);

    $map->mapOptions = array_merge(
        (array) $map->mapOptions,
        ['colorScheme' => 'DARK']
    );
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('"mapId": "abc123mapid"')
        ->toContain('"colorScheme": "DARK"');
});

test('generated code includes restriction when enabled', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $map->mapOptions = array_merge(
        (array) $map->mapOptions,
        [
            'restrictionEnabled' => true,
            'restrictionSouth' => '-33.9',
            'restrictionWest' => '151.0',
            'restrictionNorth' => '-33.8',
            'restrictionEast' => '151.3',
            'restrictionStrictBounds' => true,
        ]
    );
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('"restriction"')
        ->toContain('"latLngBounds"')
        ->toContain('"strictBounds": true');
});

test('generated code omits advanced options when at defaults', function () {
    $map = Map::factory()->create();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->not->toContain('"gestureHandling"')
        ->not->toContain('"controlSize"')
        ->not->toContain('"heading"')
        ->not->toContain('"tilt"')
        ->not->toContain('"backgroundColor"')
        ->not->toContain('"restriction"');
});

test('generated code includes advanced options when set', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $map->mapOptions = array_merge(
        (array) $map->mapOptions,
        [
            'gestureHandling' => 'cooperative',
            'controlSize' => 32,
            'heading' => 90,
            'tilt' => 45,
            'backgroundColor' => '#ff0000',
            'minZoom' => '3',
            'maxZoom' => '18',
        ]
    );
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('"gestureHandling": "cooperative"')
        ->toContain('"controlSize": 32')
        ->toContain('"heading": 90')
        ->toContain('"tilt": 45')
        ->toContain('"backgroundColor": "#ff0000"')
        ->toContain('"minZoom": 3')
        ->toContain('"maxZoom": 18');
});

test('generated code uses json_encode for marker info window content', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $map->markers = json_encode([
        [
            'title' => 'Test "Marker"',
            'icon' => '',
            'lat' => 51.5,
            'lng' => -0.1,
            'infoWindow' => [
                'content' => '<h1>Hello</h1><p>World & "friends"</p>',
            ],
        ],
    ]);
    $map->save();
    $map->refresh();

    $code = (new MapCodeGenerator($map))->generate();

    expect($code)
        ->toContain('new google.maps.Marker(')
        ->toContain('new google.maps.InfoWindow(')
        ->toContain('addListener');
});
