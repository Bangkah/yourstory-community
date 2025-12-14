<?php

namespace App\Policies;

use App\Models\Story;
use App\Models\User;

class StoryPolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Story $story): bool
    {
        if ($story->is_published) {
            return true;
        }

        return $user !== null && $user->id === $story->user_id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Story $story): bool
    {
        return $this->canManage($user, $story);
    }

    public function delete(User $user, Story $story): bool
    {
        return $this->canManage($user, $story);
    }

    protected function canManage(User $user, Story $story): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_MODERATOR], true)) {
            return true;
        }

        return $story->user_id === $user->id;
    }
}
