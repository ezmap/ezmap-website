<?php

namespace App\Policies;

use App\Models\Map;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MapPolicy
{
  use HandlesAuthorization;

  /**
   * Create a new policy instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  public function edit(User $user, Map $map)
  {
    if ($user->id != $map->user_id)
    {
      return $this->deny("You are not allowed to edit this map.");
    }

    return $map->user_id == $user->id;
  }

  public function update(User $user, Map $map)
  {
    if ($user->id != $map->user_id)
    {
      return $this->deny("You are not allowed to edit this map.");
    }

    return $map->user_id == $user->id;
  }

  public function destroy(User $user, Map $map)
  {
    if ($user->id != $map->user_id)
    {
      return $this->deny("You are not allowed to delete this map.");
    }

    return $map->user_id == $user->id;
  }

  public function delete(User $user, Map $map)
  {
    if ($user->id != $map->user_id)
    {
      return $this->deny("You are not allowed to delete this map.");
    }

    return $map->user_id == $user->id;
  }

  public function undelete(User $user, Map $map)
  {
    if ($user->id != $map->user_id)
    {
      return $this->deny("You are not allowed to restore this map.");
    }

    return $map->user_id == $user->id;
  }

  public function image(User $user, Map $map)
  {
    if (!($map->user_id == $user->id && $map->apiKey !== ""))
    {
      return $this->deny("Images for this map can only be obtained by the owner of the map with a valid Google Maps API key.");
    }

    return $map->user_id == $user->id && $map->apiKey !== "";
  }

  public function download(User $user, Map $map)
  {
    if (!($map->user_id == $user->id && $map->apiKey !== ""))
    {
      return $this->deny("Images for this map can only be downloaded by the owner of the map with a valid Google Maps API key.");
    }

    return $map->user_id == $user->id && $map->apiKey !== "";
  }
}
