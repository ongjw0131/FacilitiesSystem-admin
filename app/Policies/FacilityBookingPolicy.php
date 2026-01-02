<?php

namespace App\Policies;

use App\Models\FacilityBooking;
use App\Models\User;

class FacilityBookingPolicy
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
        return $user->role === 'admin';
    }

    public function view(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'admin' || $booking->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, FacilityBooking $booking): bool
    {
        // We cancel instead of hard delete
        return $user->role === 'admin';
    }

    public function approve(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'admin';
    }

    public function reject(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'admin';
    }

    public function cancel(User $user, FacilityBooking $booking): bool
    {
        return $user->role === 'admin' || $booking->created_by === $user->id;
    }
}
