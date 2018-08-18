<?php

namespace App\Http\Controllers;

use App\Map;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller
{

  public function getMaps($email, $apiKey)
  {
    $user = User::where(['email' => $email, 'apikey' => $apiKey])->firstOrFail();

    return $user->maps;
  }

  public function getMapCode($email, $apiKey, Map $map){
    $user = User::where(['email' => $email, 'apikey' => $apiKey])->firstOrFail();

    if ($map->user->id === $user->id)
    {
      if ($map->embeddable)
      {
        return view('api.embedcode', compact('map'));
      }
      return view('api.mapcode', compact('map'));
    }

  }

}
