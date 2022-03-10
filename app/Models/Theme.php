<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
  protected $fillable = ['snazzy_id', 'name', 'description', 'tags', 'colors', 'url', 'imageUrl', 'json', 'author'];
  protected $casts = [
      'author' => 'object',
      'tags'   => 'array',
      'colors' => 'array',
  ];


  public function toImageParams()
  {
    $response = "";
    $styles   = $this->fromJson(
        trim(str_replace("\r\n", "", $this->json), ',')
    );
    if($styles === null)
    {
      throw new \Exception(json_last_error_msg() . ": Theme {$this->id}");
    }
    foreach ($styles as $style)
    {
      if(!empty($style["stylers"]))
      {
        $response .= "&style=";
        if (isset($style['featureType']))
        {
          $response .= "feature:" . $style['featureType'];
        }
        if (isset($style['elementType']))
        {
          $response .= "|element:" . $style['elementType'];
        }

        foreach ($style['stylers'] as $subStyle)
        {
          foreach ($subStyle as $key => $value)
          {
            if ($key === 'invert_lightness')
            {
              if ($value)
              {
                $value = "true";
              }
            }
          }

          $response .= "|{$key}:" . str_replace('#', '0x', $value);
        }
      }
    }

    return $response;
  }

}
