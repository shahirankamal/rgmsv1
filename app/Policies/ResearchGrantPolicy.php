<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ResearchGrant;

class ResearchGrantPolicy
{
    public function view(User $user, ResearchGrant $grant)
    {
        return $user->isAdmin() || 
               $grant->leader_id === $user->academician->id ||
               $grant->members->contains($user->academician);
    }

    public function update(User $user, ResearchGrant $grant)
    {
        return $user->isAdmin() || $grant->leader_id === $user->academician->id;
    }
} 