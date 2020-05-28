<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_login_at', 'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getIsAdminAttribute()
    {
        return $this->id == 1;
    }

    public function maps()
    {
        return $this->hasMany(Map::class);
    }

    public function icons()
    {
        return $this->hasMany(Icon::class);
    }
}
