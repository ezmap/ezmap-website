<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $fillable = [
        'mapContainer',
        'width',
        'height',
        'responsiveMap',
        'latitude',
        'longitude',
        'markers',
        'mapOptions',
        'theme_id',
    ];

    protected $casts = [
        'mapOptions'    => 'object',
        'markers'       => 'object',
        'responsiveMap' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

}
