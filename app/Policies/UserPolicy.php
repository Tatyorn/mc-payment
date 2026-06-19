<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function finance(User $user): bool
    {
        return $user->role->isFinance();
    }
}
