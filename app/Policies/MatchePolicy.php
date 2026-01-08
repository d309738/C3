<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Matche;

class MatchePolicy
{
    public function update(User $user, Matche $match)
    {
        // For now allow any authenticated user. Adjust to admins/specific roles later.
        return (bool) $user;
    }

    public function delete(User $user, Matche $match)
    {
        // Same for delete: allow authenticated users for now
        return (bool) $user;
    }
}
