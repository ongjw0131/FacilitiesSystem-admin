<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;

class FacilityPolicy
{
    /**
     * Allow admins to bypass other checks.
     */
    public function before(User $user): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Facility $facility): bool
    {
        return $user !== null;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Facility $facility): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Facility $facility): bool
    {
        // Deactivation follows the same permission as update
        return $user->role === 'admin';
    }
}
