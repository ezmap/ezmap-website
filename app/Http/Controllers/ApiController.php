<?php

namespace App\Http\Controllers;

use App\Map;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller
{

  private function checkApiAvailable($email, $apiKey)
  {
    $user = User::where(['email' => $email])->firstOrFail();

    if ($user->apikey === "" || $apiKey !== $user->apikey)
    {
      abort(404);
    }
  }

  public function getMaps($email, $apiKey)
  {
    $this->checkApiAvailable($email, $apiKey);

    $user = User::where(['email' => $email, 'apikey' => $apiKey])->firstOrFail();

    return $user->maps;
  }

  public function getMapCode( $email, $apiKey, Map $map)
  {
    $this->checkApiAvailable($email, $apiKey);

    $user = User::where(['email' => $email, 'apikey' => $apiKey])->firstOrFail();

    if ($map->user->id === $user->id)
    {
      if ($map->embeddable)
      {
        return view('api.embedcode', compact('map'));
      }

      return view('api.mapcode', compact('map'));
    }

    return "";

  }


}