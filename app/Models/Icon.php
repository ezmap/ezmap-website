<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $fillable = ['name', 'url', 'user_id'];

    protected $visible = ['id', 'name', 'url'];

}
