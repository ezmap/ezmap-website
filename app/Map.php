<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $fillable = [
        'title',
        'mapContainer',
        'width',
        'height',
        'responsiveMap',
        'latitude',
        'longitude',
        'markers',
        'mapOptions',
        'theme_id',
        'embeddable',
    ];

    protected $casts = [
        'mapOptions'    => 'object',
        'markers'       => 'object',
        'responsiveMap' => 'boolean',
        'embeddable'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Literally haven't any fucking clue why I need to double-decode this shit.
     *
     * @param $markers
     * @return \Illuminate\Support\Collection
     */
    public function getMarkersAttribute($markers)
    {
        if (!is_array(json_decode($markers)))
        {
            return collect(json_decode(json_decode($markers)));
        }

        return collect(json_decode($markers));
    }

    public function getMapOptionsAttribute($options)
    {
//        $options = collect(json_decode($options))->transform(function ($option)
//        {
//            $option = ($option === 'true') ? true : $option;
//            $option = ($option === 'false') ? false : $option;
//
//            return $option;
//        })->all();
//        dd(json_decode($options));
        return json_decode($options);
    }

    public function code()
    {
        $disableDoubleClickZoom = $this->mapOptions->doubleClickZoom ? 'false' : 'true';
        $styles                 = ($this->theme_id > 0) ? ",\n                \"styles\": " . $this->theme->json : '';
        $output                 = "
    function init() {
            var mapOptions = {
                \"center\": {\"lat\": {$this->latitude}, \"lng\": {$this->longitude}},                
                \"clickableIcons\": {$this->mapOptions->clickableIcons},
                \"disableDoubleClickZoom\": {$disableDoubleClickZoom},
                \"draggable\": {$this->mapOptions->draggable},
                \"fullscreenControl\": {$this->mapOptions->showFullScreenControl},
                \"keyboardShortcuts\": {$this->mapOptions->keyboardShortcuts},
                \"mapMaker\": {$this->mapOptions->mapMakerTiles},
                \"mapTypeControl\": {$this->mapOptions->showMapTypeControl},
                \"mapTypeControlOptions\": { style : {$this->mapOptions->mapTypeControlStyle} },
                \"mapTypeId\": \"{$this->mapOptions->mapTypeId}\",
                \"rotateControl\": true,
                \"scaleControl\": {$this->mapOptions->showScaleControl},
                \"scrollwheel\": {$this->mapOptions->scrollWheel},
                \"streetViewControl\": {$this->mapOptions->showStreetViewControl},
                \"zoom\": {$this->mapOptions->zoomLevel},
                \"zoomControl\": {$this->mapOptions->showZoomControl}{$styles}
            };
      var mapElement = document.getElementById('ez-map');
      var map = new google.maps.Map(mapElement, mapOptions);";
        $output .= $this->markersLoop();
        $output .= "\n      google.maps.event.addDomListener(window, 'resize', function() { 
        var center = map.getCenter(); 
        google.maps.event.trigger(map, 'resize'); 
        map.setCenter(center); 
      });
    }
    google.maps.event.addDomListener(window, 'load', init);
";

        return $output;
    }

    private function markersLoop()
    {
        $str = "";
        for ($i = 0; $i < count($this->markers); $i++)
        {
            $marker = $this->markers[$i];
            $str .= "\n      var marker{$i} = new google.maps.Marker({title: \"{$marker->title}\", icon: \"{$marker->icon}\", position: new google.maps.LatLng({$marker->lat},{$marker->lng}), map: map});";
            if ($marker->infoWindow->content)
            {
                $str .= "\n      var infowindow{$i} = new google.maps.InfoWindow({content: " . json_encode($marker->infoWindow->content) . ",map: map});";
                $str .= "\n      marker{$i}.addListener('click', function () { infowindow{$i}.open(map, marker{$i}) ;});infowindow{$i}.close();";
            }
        }

        return $str;
    }

}
