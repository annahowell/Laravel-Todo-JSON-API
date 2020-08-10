<?php

namespace App\Policies;

use App\Tag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->id == $tag->created_by;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->id == $tag->created_by;
    }
}
