<?php

namespace App\Services;

use App\Models\Map;

class MapCodeGenerator
{
    public function __construct(protected Map $map)
    {
    }

    public function generate(): string
    {
        $opts = $this->mapOptions();
        $optsJson = $this->formatOptions($opts);
        $funcName = "init{$this->map->id}";

        $lines = [];
        $lines[] = "function {$funcName}() {";
        $lines[] = "  var mapOptions = {$optsJson};";
        $lines[] = "  var mapElement = document.getElementById('{$this->map->mapContainer}');";
        $lines[] = "  var map = new google.maps.Map(mapElement, mapOptions);";

        // Data layers
        foreach (['traffic' => 'TrafficLayer', 'transit' => 'TransitLayer', 'bicycling' => 'BicyclingLayer'] as $key => $class) {
            if ($this->bool($this->opt("{$key}Layer"), false)) {
                $lines[] = "  new google.maps.{$class}().setMap(map);";
            }
        }

        // KML Layer
        $kmlUrl = $this->opt('kmlUrl');
        if (!empty($kmlUrl)) {
            $kmlUrlEscaped = addslashes($kmlUrl);
            $lines[] = "  new google.maps.KmlLayer({url: '{$kmlUrlEscaped}', map: map}).setMap(map);";
        }

        // GeoJSON Layer
        $geoJsonUrl = $this->opt('geoJsonUrl');
        if (!empty($geoJsonUrl)) {
            $geoJsonUrlEscaped = addslashes($geoJsonUrl);
            $lines[] = "  map.data.loadGeoJson('{$geoJsonUrlEscaped}');";
        }

        // Markers
        $lines = array_merge($lines, $this->markerLines());

        // Marker Clustering
        if ($this->bool($this->opt('markerClustering'), false) && $this->map->markers->count() > 0) {
            $markerVars = implode(', ', array_map(fn ($i) => "marker{$i}", range(0, $this->map->markers->count() - 1)));
            $lines[] = "  new markerClusterer.MarkerClusterer({markers: [{$markerVars}], map: map});";
        }

        // Heatmap
        $lines = array_merge($lines, $this->heatmapLines());

        // Responsive resize handler
        $lines[] = "  window.addEventListener('resize', function() {";
        $lines[] = "    var center = map.getCenter();";
        $lines[] = "    google.maps.event.trigger(map, 'resize');";
        $lines[] = "    map.setCenter(center);";
        $lines[] = "  });";
        $lines[] = "}";
        $lines[] = "window.addEventListener('load', {$funcName});";

        return "\n" . implode("\n", array_map(fn ($l) => "    {$l}", $lines)) . "\n";
    }

    protected function mapOptions(): array
    {
        $opts = [
            'center' => ['lat' => (float) $this->map->latitude, 'lng' => (float) $this->map->longitude],
            'clickableIcons' => $this->bool($this->opt('clickableIcons')),
            'disableDoubleClickZoom' => !$this->bool($this->opt('doubleClickZoom')),
            'draggable' => $this->bool($this->opt('draggable')),
            'fullscreenControl' => $this->bool($this->opt('showFullScreenControl')),
            'keyboardShortcuts' => $this->bool($this->opt('keyboardShortcuts')),
            'mapTypeControl' => $this->bool($this->opt('showMapTypeControl')),
            'mapTypeId' => $this->opt('mapTypeId') ?? 'roadmap',
            'rotateControl' => $this->bool($this->opt('rotateControl'), true),
            'cameraControl' => $this->bool($this->opt('cameraControl'), true),
            'scaleControl' => $this->bool($this->opt('showScaleControl')),
            'scrollwheel' => $this->bool($this->opt('scrollWheel')),
            'streetViewControl' => $this->bool($this->opt('showStreetViewControl')),
            'zoom' => (int) $this->opt('zoomLevel'),
            'zoomControl' => $this->bool($this->opt('showZoomControl')),
        ];

        // mapTypeControlOptions
        $mapTypeOpts = ['style' => (int) ($this->opt('mapTypeControlStyle') ?? 0)];
        $mapTypePos = $this->opt('mapTypeControlPosition');
        if (!empty($mapTypePos)) {
            $mapTypeOpts['position'] = "%%CTRL_POS:{$mapTypePos}%%";
        }
        $opts['mapTypeControlOptions'] = $mapTypeOpts;

        // Snazzy Maps theme styles (mutually exclusive with Map ID)
        $hasGoogleMapId = !empty($this->map->google_map_id);
        if (!$hasGoogleMapId && $this->map->theme_id > 0 && $this->map->theme) {
            $opts['styles'] = json_decode($this->map->theme->json, true);
        }

        // Google Cloud Map ID + colorScheme
        if ($hasGoogleMapId) {
            $opts['mapId'] = $this->map->google_map_id;
            $opts['colorScheme'] = $this->opt('colorScheme') ?? 'FOLLOW_SYSTEM';
        }

        // Optional advanced settings — only include when non-default
        $gestureHandling = $this->opt('gestureHandling') ?? 'auto';
        if ($gestureHandling !== 'auto') {
            $opts['gestureHandling'] = $gestureHandling;
        }

        $controlSize = (int) ($this->opt('controlSize') ?? 0);
        if ($controlSize > 0) {
            $opts['controlSize'] = $controlSize;
        }

        foreach (['minZoom', 'maxZoom'] as $zoomProp) {
            $val = $this->opt($zoomProp);
            if ($val !== null && $val !== '') {
                $opts[$zoomProp] = (int) $val;
            }
        }

        $heading = (int) ($this->opt('heading') ?? 0);
        if ($heading !== 0) {
            $opts['heading'] = $heading;
        }

        $tilt = (int) ($this->opt('tilt') ?? 0);
        if ($tilt !== 0) {
            $opts['tilt'] = $tilt;
        }

        $bgColor = $this->opt('backgroundColor') ?? '';
        if ($bgColor !== '') {
            $opts['backgroundColor'] = $bgColor;
        }

        // Map restriction
        if (!empty($this->opt('restrictionEnabled')) &&
            ($this->opt('restrictionSouth') ?? '') !== '' &&
            ($this->opt('restrictionWest') ?? '') !== '' &&
            ($this->opt('restrictionNorth') ?? '') !== '' &&
            ($this->opt('restrictionEast') ?? '') !== '') {
            $opts['restriction'] = [
                'latLngBounds' => [
                    'south' => (float) $this->opt('restrictionSouth'),
                    'west' => (float) $this->opt('restrictionWest'),
                    'north' => (float) $this->opt('restrictionNorth'),
                    'east' => (float) $this->opt('restrictionEast'),
                ],
                'strictBounds' => !empty($this->opt('restrictionStrictBounds')),
            ];
        }

        // Control positions (use placeholder for post-processing)
        $positionMap = [
            'fullscreenControlPosition' => 'fullscreenControlOptions',
            'zoomControlPosition' => 'zoomControlOptions',
            'streetViewControlPosition' => 'streetViewControlOptions',
            'rotateControlPosition' => 'rotateControlOptions',
            'cameraControlPosition' => 'cameraControlOptions',
        ];
        foreach ($positionMap as $prop => $optKey) {
            $pos = $this->opt($prop) ?? '';
            if (!empty($pos)) {
                $opts[$optKey] = ['position' => "%%CTRL_POS:{$pos}%%"];
            }
        }

        return $opts;
    }

    protected function formatOptions(array $opts): string
    {
        $json = json_encode($opts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Replace placeholder strings with Google Maps ControlPosition enum references
        $json = preg_replace(
            '/"%%CTRL_POS:(TOP_LEFT|TOP_CENTER|TOP_RIGHT|LEFT_TOP|LEFT_CENTER|LEFT_BOTTOM|RIGHT_TOP|RIGHT_CENTER|RIGHT_BOTTOM|BOTTOM_LEFT|BOTTOM_CENTER|BOTTOM_RIGHT)%%"/',
            'google.maps.ControlPosition.$1',
            $json
        );

        // mapTypeControlOptions.style should be a raw number, not a string
        // (json_encode already handles this since we cast to int)

        return $json;
    }

    protected function markerLines(): array
    {
        $lines = [];
        $markers = $this->map->markers;

        for ($i = 0; $i < count($markers); $i++) {
            $marker = $markers[$i];
            $markerOpts = [
                'title' => $marker->title ?? '',
                'icon' => $marker->icon ?? '',
                'position' => "%%RAW:new google.maps.LatLng({$marker->lat},{$marker->lng})%%",
                'map' => '%%RAW:map%%',
            ];

            $markerJson = json_encode($markerOpts, JSON_UNESCAPED_SLASHES);
            // Replace raw placeholders
            $markerJson = preg_replace('/"%%RAW:(.+?)%%"/', '$1', $markerJson);

            $lines[] = "  var marker{$i} = new google.maps.Marker({$markerJson});";

            if (!empty($marker->infoWindow->content)) {
                $infoOpts = [
                    'content' => $marker->infoWindow->content,
                    'map' => '%%RAW:map%%',
                ];
                $infoJson = json_encode($infoOpts, JSON_UNESCAPED_SLASHES);
                $infoJson = preg_replace('/"%%RAW:(.+?)%%"/', '$1', $infoJson);

                $lines[] = "  var infowindow{$i} = new google.maps.InfoWindow({$infoJson});";
                $lines[] = "  marker{$i}.addListener('click', function() { infowindow{$i}.open(map, marker{$i}); });";
                $lines[] = "  infowindow{$i}.close();";
            }
        }

        return $lines;
    }

    protected function heatmapLines(): array
    {
        $lines = [];
        $heatmap = $this->map->heatmap;

        if ($heatmap !== null && count($heatmap) > 0) {
            $dataPoints = [];
            foreach ($heatmap as $hotspot) {
                $lat = $hotspot->weightedLocation->location->lat;
                $lng = $hotspot->weightedLocation->location->lng;
                $weight = $hotspot->weightedLocation->weight;
                $dataPoints[] = "{ location: new google.maps.LatLng({$lat},{$lng}), weight: {$weight} }";
            }
            $dataStr = implode(', ', $dataPoints);
            $lines[] = "  var heatmap = new google.maps.visualization.HeatmapLayer({ data: [{$dataStr}] });";
            $lines[] = "  heatmap.setMap(map);";

            $heatmapOpts = json_encode($this->map->heatmapLayer, JSON_UNESCAPED_SLASHES);
            $lines[] = "  heatmap.setOptions({$heatmapOpts});";
        }

        return $lines;
    }

    /**
     * Get a value from mapOptions.
     */
    protected function opt(string $key): mixed
    {
        return $this->map->mapOptions->$key ?? null;
    }

    /**
     * Cast a mapOptions value to boolean.
     */
    protected function bool(mixed $value, bool $default = true): bool
    {
        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
