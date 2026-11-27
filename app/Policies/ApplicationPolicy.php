<?php

namespace App\Policies;

use App\ApplicationStatus;
use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'student';
    }

    public function view(User $user, Application $application): bool
    {
        return $user->role === 'student' && $application->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'student';
    }

    public function update(User $user, Application $application): bool
    {
        return $this->view($user, $application)
            && $application->status === ApplicationStatus::Draft;
    }
}
