<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['snazzy_id', 'name', 'description', 'url', 'imageUrl', 'json', 'author'];
    protected $casts = [
        'author' => 'object',
    ];
}
