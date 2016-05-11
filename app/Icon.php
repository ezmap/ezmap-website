<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $fillable = ['name', 'url', 'user_id'];

    protected $visible = ['name', 'url'];

}
