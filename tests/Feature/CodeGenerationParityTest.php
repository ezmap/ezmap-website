<?php

/**
 * Code Generation Parity Test
 *
 * Ensures MapCodeGenerator (PHP, server-side) and map-editor.js generatedCode
 * getter (JS, client-side) support the same set of features. If you add a new
 * map option, control position, or data layer to one, this test will fail until
 * you add it to the other.
 */

function getGeneratedCodeBlock(): string
{
    $jsSource = file_get_contents(base_path('resources/js/map-editor.js'));
    $genCodeStart = strpos($jsSource, 'get generatedCode()');
    $genCodeEnd = strpos($jsSource, 'get generatedEmbedCode()');
    return substr($jsSource, $genCodeStart, $genCodeEnd - $genCodeStart);
}

function getPhpSource(): string
{
    return file_get_contents(base_path('app/Services/MapCodeGenerator.php'));
}

test('MapCodeGenerator and map-editor.js support the same core map options', function () {
    $php = getPhpSource();
    $js = getGeneratedCodeBlock();

    $coreOptions = [
        'center', 'clickableIcons', 'disableDoubleClickZoom', 'draggable',
        'fullscreenControl', 'keyboardShortcuts', 'mapTypeControl',
        'mapTypeControlOptions', 'mapTypeId', 'rotateControl', 'cameraControl',
        'scaleControl', 'scrollwheel', 'streetViewControl', 'zoom', 'zoomControl',
    ];

    $missingInPhp = [];
    $missingInJs = [];
    foreach ($coreOptions as $opt) {
        if (!str_contains($php, "'{$opt}'")) $missingInPhp[] = $opt;
        if (!str_contains($js, $opt)) $missingInJs[] = $opt;
    }

    expect($missingInPhp)->toBe([], 'MapCodeGenerator missing core options: ' . implode(', ', $missingInPhp));
    expect($missingInJs)->toBe([], 'map-editor.js generatedCode missing core options: ' . implode(', ', $missingInJs));
});

test('MapCodeGenerator and map-editor.js support the same conditional options', function () {
    $php = getPhpSource();
    $js = getGeneratedCodeBlock();

    $conditionalOptions = [
        'gestureHandling', 'controlSize', 'minZoom', 'maxZoom',
        'heading', 'tilt', 'backgroundColor', 'restriction',
        'styles', 'mapId', 'colorScheme',
    ];

    $missingInPhp = [];
    $missingInJs = [];
    foreach ($conditionalOptions as $opt) {
        if (!str_contains($php, "'{$opt}'")) $missingInPhp[] = $opt;
        if (!str_contains($js, $opt)) $missingInJs[] = $opt;
    }

    expect($missingInPhp)->toBe([], 'MapCodeGenerator missing conditional options: ' . implode(', ', $missingInPhp));
    expect($missingInJs)->toBe([], 'map-editor.js generatedCode missing conditional options: ' . implode(', ', $missingInJs));
});

test('MapCodeGenerator and map-editor.js support the same control positions', function () {
    $php = getPhpSource();
    $js = getGeneratedCodeBlock();

    $controlPositions = [
        'fullscreenControlOptions', 'zoomControlOptions',
        'streetViewControlOptions', 'rotateControlOptions',
        'cameraControlOptions',
    ];

    $missingInPhp = [];
    $missingInJs = [];
    foreach ($controlPositions as $opt) {
        if (!str_contains($php, "'{$opt}'")) $missingInPhp[] = $opt;
        if (!str_contains($js, $opt)) $missingInJs[] = $opt;
    }

    expect($missingInPhp)->toBe([], 'MapCodeGenerator missing control positions: ' . implode(', ', $missingInPhp));
    expect($missingInJs)->toBe([], 'map-editor.js generatedCode missing control positions: ' . implode(', ', $missingInJs));
});

test('MapCodeGenerator and map-editor.js support the same data layers', function () {
    $php = getPhpSource();
    $js = getGeneratedCodeBlock();

    $layers = ['TrafficLayer', 'TransitLayer', 'BicyclingLayer', 'KmlLayer', 'loadGeoJson', 'MarkerClusterer'];

    $missingInPhp = [];
    $missingInJs = [];
    foreach ($layers as $layer) {
        if (!str_contains($php, $layer)) $missingInPhp[] = $layer;
        if (!str_contains($js, $layer)) $missingInJs[] = $layer;
    }

    expect($missingInPhp)->toBe([], 'MapCodeGenerator missing data layers: ' . implode(', ', $missingInPhp));
    expect($missingInJs)->toBe([], 'map-editor.js generatedCode missing data layers: ' . implode(', ', $missingInJs));
});

test('MapCodeGenerator and map-editor.js both generate markers and heatmaps', function () {
    $php = getPhpSource();
    $js = getGeneratedCodeBlock();

    // PHP service should reference Marker, InfoWindow, and HeatmapLayer
    expect($php)
        ->toContain('google.maps.Marker')
        ->toContain('google.maps.InfoWindow')
        ->toContain('HeatmapLayer');

    // JS generatedCode should call its marker and heatmap loop methods
    expect($js)
        ->toContain('markersLoop()')
        ->toContain('heatmapLoop()');
});
