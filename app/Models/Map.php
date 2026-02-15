<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\MapCodeGenerator;

class Map extends Model
{
  use HasFactory, SoftDeletes;

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
      'google_map_id',
      'container_border_radius',
      'container_border',
  ];

  protected $casts = [
      'mapOptions'    => 'object',
      'markers'       => 'object',
      'heatmap'       => 'object',
      'heatmapLayer'  => 'object',
      'responsiveMap' => 'boolean',
      'embeddable'    => 'boolean',
      'deleted_at'    => 'datetime',
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
    return (new MapCodeGenerator($this))->generate();
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
