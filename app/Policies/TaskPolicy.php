<?php

namespace App\Policies;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     */
    public function update(User $user, Task $task): bool
    {
        // Any authenticated user can modify a task
        return true;
    }
    

    /**
     * Determine whether the user can delete the model
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id == $task->created_by;
    }
}
