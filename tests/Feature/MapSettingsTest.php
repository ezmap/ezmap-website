<?php

use App\Models\Map;
use App\Models\User;

test('container styling is saved on update', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => ['zoom' => 10, 'mapTypeId' => 'roadmap'],
            'containerBorderRadius' => '12',
            'containerBorder' => '2px solid #333',
        ])->assertRedirect();

    $map->refresh();
    expect($map->container_border_radius)->toBe('12');
    expect($map->container_border)->toBe('2px solid #333');
});

test('container styling defaults when not provided', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create([
        'user_id' => $user->id,
        'container_border_radius' => '8',
        'container_border' => '1px solid red',
    ]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => ['zoom' => 10, 'mapTypeId' => 'roadmap'],
        ])->assertRedirect();

    $map->refresh();
    expect($map->container_border_radius)->toBe('0');
    expect($map->container_border)->toBe('');
});

test('google map id and color scheme are saved on update', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                'colorScheme' => 'DARK',
            ],
            'google_map_id' => 'abc123def',
        ])->assertRedirect();

    $map->refresh();
    expect($map->google_map_id)->toBe('abc123def');
    expect($map->mapOptions->colorScheme)->toBe('DARK');
});

test('google map id can be cleared', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create([
        'user_id' => $user->id,
        'google_map_id' => 'existing-map-id',
    ]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => ['zoom' => 10, 'mapTypeId' => 'roadmap'],
            'google_map_id' => '',
        ])->assertRedirect();

    $map->refresh();
    expect($map->google_map_id)->toBeNull();
});

test('boolean map options save correctly when toggled off', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                // Boolean checkbox keys omitted = unchecked
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->showFullScreenControl)->toBe('false');
    expect($map->mapOptions->showMapTypeControl)->toBe('false');
    expect($map->mapOptions->showZoomControl)->toBe('false');
    expect($map->mapOptions->showStreetViewControl)->toBe('false');
    expect($map->mapOptions->showScaleControl)->toBe('false');
    expect($map->mapOptions->scrollWheel)->toBe('false');
    expect($map->mapOptions->keyboardShortcuts)->toBe('false');
    expect($map->mapOptions->clickableIcons)->toBe('false');
    expect($map->mapOptions->draggable)->toBe('false');
    expect($map->mapOptions->doubleClickZoom)->toBe('false');
    expect($map->mapOptions->rotateControl)->toBe('false');
});

test('boolean map options save correctly when toggled on', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                // Boolean checkbox keys present = checked
                'showFullScreenControl' => 'on',
                'showMapTypeControl' => 'on',
                'showZoomControl' => 'on',
                'showStreetViewControl' => 'on',
                'showScaleControl' => 'on',
                'scrollWheel' => 'on',
                'keyboardShortcuts' => 'on',
                'clickableIcons' => 'on',
                'draggable' => 'on',
                'doubleClickZoom' => 'on',
                'rotateControl' => 'on',
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->showFullScreenControl)->toBe('true');
    expect($map->mapOptions->showMapTypeControl)->toBe('true');
    expect($map->mapOptions->showZoomControl)->toBe('true');
    expect($map->mapOptions->showStreetViewControl)->toBe('true');
    expect($map->mapOptions->showScaleControl)->toBe('true');
    expect($map->mapOptions->scrollWheel)->toBe('true');
    expect($map->mapOptions->keyboardShortcuts)->toBe('true');
    expect($map->mapOptions->clickableIcons)->toBe('true');
    expect($map->mapOptions->draggable)->toBe('true');
    expect($map->mapOptions->doubleClickZoom)->toBe('true');
    expect($map->mapOptions->rotateControl)->toBe('true');
});

test('advanced map options are saved correctly', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                'gestureHandling' => 'cooperative',
                'controlSize' => '40',
                'minZoom' => '3',
                'maxZoom' => '18',
                'heading' => '45',
                'tilt' => '45',
                'backgroundColor' => '#ff0000',
                'fullscreenControlPosition' => 'TOP_RIGHT',
                'zoomControlPosition' => 'LEFT_BOTTOM',
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->gestureHandling)->toBe('cooperative');
    expect($map->mapOptions->controlSize)->toBe(40);
    expect($map->mapOptions->minZoom)->toBe('3');
    expect($map->mapOptions->maxZoom)->toBe('18');
    expect($map->mapOptions->heading)->toBe(45);
    expect($map->mapOptions->tilt)->toBe(45);
    expect($map->mapOptions->backgroundColor)->toBe('#ff0000');
    expect($map->mapOptions->fullscreenControlPosition)->toBe('TOP_RIGHT');
    expect($map->mapOptions->zoomControlPosition)->toBe('LEFT_BOTTOM');
});

test('restriction bounds are saved correctly', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                'restrictionEnabled' => 'on',
                'restrictionStrictBounds' => 'on',
                'restrictionSouth' => '-33.8',
                'restrictionWest' => '151.0',
                'restrictionNorth' => '-33.7',
                'restrictionEast' => '151.3',
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->restrictionEnabled)->toBeTrue();
    expect($map->mapOptions->restrictionStrictBounds)->toBeTrue();
    expect($map->mapOptions->restrictionSouth)->toBe('-33.8');
    expect($map->mapOptions->restrictionWest)->toBe('151.0');
    expect($map->mapOptions->restrictionNorth)->toBe('-33.7');
    expect($map->mapOptions->restrictionEast)->toBe('151.3');
});

test('valid http/https URLs are saved for KML and GeoJSON', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                'kmlUrl' => 'https://example.com/data.kml',
                'geoJsonUrl' => 'http://example.com/data.geojson',
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->kmlUrl)->toBe('https://example.com/data.kml');
    expect($map->mapOptions->geoJsonUrl)->toBe('http://example.com/data.geojson');
});

test('invalid and dangerous URLs are rejected for KML and GeoJSON', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $cases = [
        'javascript:alert(1)',
        'file:///etc/passwd',
        'data:text/html,<script>alert(1)</script>',
        'not-a-url',
        'ftp://example.com/file.kml',
    ];

    foreach ($cases as $badUrl) {
        $this->actingAs($user)
            ->put(route('map.update', $map), [
                'title' => $map->title,
                'mapContainer' => 'map',
                'width' => 600,
                'height' => 400,
                'latitude' => $map->latitude,
                'longitude' => $map->longitude,
                'markers' => json_encode([]),
                'heatmap' => json_encode([]),
                'heatmapLayer' => json_encode((object) []),
                'mapOptions' => [
                    'zoom' => 10,
                    'mapTypeId' => 'roadmap',
                    'kmlUrl' => $badUrl,
                    'geoJsonUrl' => $badUrl,
                ],
            ])->assertRedirect();

        $map->refresh();
        expect($map->mapOptions->kmlUrl)->toBe('', "kmlUrl should be empty for: {$badUrl}");
        expect($map->mapOptions->geoJsonUrl)->toBe('', "geoJsonUrl should be empty for: {$badUrl}");
    }
});

test('null and empty URLs are handled for KML and GeoJSON', function () {
    $user = User::factory()->create();
    $map = Map::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('map.update', $map), [
            'title' => $map->title,
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'latitude' => $map->latitude,
            'longitude' => $map->longitude,
            'markers' => json_encode([]),
            'heatmap' => json_encode([]),
            'heatmapLayer' => json_encode((object) []),
            'mapOptions' => [
                'zoom' => 10,
                'mapTypeId' => 'roadmap',
                // kmlUrl and geoJsonUrl not provided (null from request)
            ],
        ])->assertRedirect();

    $map->refresh();
    expect($map->mapOptions->kmlUrl)->toBe('');
    expect($map->mapOptions->geoJsonUrl)->toBe('');
});
