<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id ||
               $project->members()
                       ->where('user_id', $user->id)
                       ->where('project_members.role', 'admin')
                       ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task)
    {
        return $user->id === $task->assigned_to // si l'utilisateur est assigné à la tâche
            || $user->id === $task->project->user_id;  // OU si c'est le propriétaire du projet
    }

    public function complete(User $user, Task $task)
    {
        return $user->id === $task->assigned_to
            || $user->id === $task->project->user_id;
    }

    public function updateStatus(User $user, Project $project)
    {
        return $user->id === $project->user_id
            || $project->members->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }


}
