<?php

namespace App\Policies;

use App\Models\Guide;
use App\Models\User;

class GuidePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(?User $user, Guide $guide): bool
    {
        if ($guide->visibility->value === 'public' && $guide->status->value === 'published') {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $user->id === $guide->user_id || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id || $user->is_admin;
    }

    public function delete(User $user, Guide $guide): bool
    {
        return $user->is_admin;
    }

    public function restore(User $user, Guide $guide): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Guide $guide): bool
    {
        return $user->is_admin;
    }
}
