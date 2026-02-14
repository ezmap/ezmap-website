<?php

namespace Database\Factories;

use App\Models\Map;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MapFactory extends Factory
{
    protected $model = Map::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'apiKey' => '',
            'mapContainer' => 'map',
            'width' => 600,
            'height' => 400,
            'responsiveMap' => true,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'markers' => [],
            'heatmap' => [],
            'heatmapLayer' => (object) [],
            'mapOptions' => (object) [
                'zoom' => 10,
                'zoomLevel' => 10,
                'mapTypeId' => 'roadmap',
                'mapTypeControlStyle' => 0,
                'doubleClickZoom' => false,
                'clickableIcons' => false,
                'draggable' => true,
                'showFullScreenControl' => false,
                'keyboardShortcuts' => false,
                'showMapTypeControl' => false,
                'showScaleControl' => false,
                'scrollWheel' => false,
                'showStreetViewControl' => false,
                'showZoomControl' => false,
            ],
            'theme_id' => 0,
            'embeddable' => false,
        ];
    }
}
