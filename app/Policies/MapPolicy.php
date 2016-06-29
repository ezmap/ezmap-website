<?php

namespace App\Policies;

use App\Map;
use App\User;
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
        return $map->user_id == $user->id;
    }

    public function update(User $user, Map $map)
    {
        return $map->user_id == $user->id;
    }

    public function destroy(User $user, Map $map)
    {
        return $map->user_id == $user->id;
    }

    public function show(User $user, Map $map)
    {
        return $map->user_id == $user->id;
    }
}
