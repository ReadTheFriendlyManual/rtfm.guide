<?php

namespace App\Policies;

use App\Models\Guide;
use App\Models\User;

class GuidePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Guide $guide): bool
    {
        if ($guide->visibility->value === 'public' && $guide->status->value === 'published') {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $user->id === $guide->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }

    public function delete(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }

    public function restore(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }

    public function forceDelete(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }
}
