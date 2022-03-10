<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Map extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'title',
      'mapContainer',
      'width',
      'height',
      'responsiveMap',
      'latitude',
      'longitude',
      'markers',
      'heatmap',
      'heatmapLayer',
      'mapOptions',
      'theme_id',
      'embeddable',
  ];

  protected $casts = [
      'mapOptions'    => 'object',
      'markers'       => 'object',
      'heatmap'       => 'object',
      'heatmapLayer'  => 'object',
      'responsiveMap' => 'boolean',
      'embeddable'    => 'boolean',
  ];

  protected $dates = ['deleted_at'];

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

  public function getHeatmapAttribute($heatmap)
  {
    if (!is_array(json_decode($heatmap)))
    {
      return collect(json_decode(json_decode($heatmap)));
    }

    return collect(json_decode($heatmap));
  }

  public function getHeatmapLayerAttribute($heatmapLayer)
  {
    return json_decode($heatmapLayer);
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
    function init{$this->id}() {
            var mapOptions = {
                \"center\": {\"lat\": {$this->latitude}, \"lng\": {$this->longitude}},                
                \"clickableIcons\": {$this->mapOptions->clickableIcons},
                \"disableDoubleClickZoom\": {$disableDoubleClickZoom},
                \"draggable\": {$this->mapOptions->draggable},
                \"fullscreenControl\": {$this->mapOptions->showFullScreenControl},
                \"keyboardShortcuts\": {$this->mapOptions->keyboardShortcuts},
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
      var mapElement = document.getElementById('{$this->mapContainer}');
      var map = new google.maps.Map(mapElement, mapOptions);";
    $output                 .= $this->markersLoop();
    $output                 .= $this->heatMapLoop();
    $output                 .= "\n      google.maps.event.addDomListener(window, 'resize', function() { 
        var center = map.getCenter(); 
        google.maps.event.trigger(map, 'resize'); 
        map.setCenter(center); 
      });
    }
    google.maps.event.addDomListener(window, 'load', init{$this->id});
";

    return $output;
  }

  private function markersLoop()
  {
    $str = "";
    for ($i = 0; $i < count($this->markers); $i++)
    {
      $marker = $this->markers[$i];
      $str    .= "\n      var marker{$i} = new google.maps.Marker({title: \"{$marker->title}\", icon: \"{$marker->icon}\", position: new google.maps.LatLng({$marker->lat},{$marker->lng}), map: map});";
      if ($marker->infoWindow->content)
      {
        $str .= "\n      var infowindow{$i} = new google.maps.InfoWindow({content: " . json_encode($marker->infoWindow->content) . ",map: map});";
        $str .= "\n      marker{$i}.addListener('click', function () { infowindow{$i}.open(map, marker{$i}) ;});infowindow{$i}.close();";
      }
    }

    return $str;
  }


  private function heatMapLoop()
  {
    $str = "";

    if ($this->heatmap !== null)
    {
      $str = "var heatmap = new google.maps.visualization.HeatmapLayer({data: [";
      foreach ($this->heatmap as $hotspot)
      {
        $str .= "{ location: new google.maps.LatLng(" . $hotspot->weightedLocation->location->lat . "," . $hotspot->weightedLocation->location->lng . "), weight: " . $hotspot->weightedLocation->weight . "},";
      }
      $str .= "]}); heatmap.setMap(map);";
      $str .= "heatmap.setOptions(" . $this->heatmapLayer . ")";
    }

    return $str;
  }

  public function getImage($extension = "png", $theme = null)
  {

    if (!in_array($extension, ['jpg', 'png', 'gif']))
    {
      $extension = 'png';
    }

    $imageUrl = "&format={$extension}";
    $imageUrl .= "&center=" . $this->latitude . ',' . $this->longitude;
    $imageUrl .= "&zoom=" . $this->mapOptions->zoomLevel;
    $imageUrl .= "&size=" . $this->width . 'x' . $this->height . "&scale=2";
    $imageUrl .= "&maptype=" . $this->mapOptions->mapTypeId;


    if ($theme !== null)
    {
      $imageUrl .= $theme->toImageParams();
    } elseif (!empty($this->theme))
    {
      $imageUrl .= $this->theme->toImageParams();
    }

    if (count($this->markers) > 0)
    {
      for ($i = 0; $i < count($this->markers); $i++)
      {
        $imageUrl .= "&markers=";
        $marker   = $this->markers[$i];
        if ($marker->icon)
        {
          $imageUrl .= "icon:" . urlencode($marker->icon);
          $imageUrl .= "|";
        }
        $imageUrl .= $marker->lat . ',' . $marker->lng;
      }

    }

    return "https://maps.googleapis.com/maps/api/staticmap?key={$this->apiKey}" . $imageUrl;
  }

}
