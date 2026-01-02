<?php

namespace App\Services\Facility;

use App\Models\FacilityBooking;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * BookingProxy - Proxy Pattern Implementation
 * 
 * Acts as a gatekeeper between the controller and BookingService.
 * Permissions:
 * - admin: Full access (list, details, create, update, cancel)
 * - student with society position (president/committee): List + booking details
 * - student without position: List only
 * 
 * This centralizes permission logic instead of scattering it across controllers.
 */
class BookingProxy
{
    /**
     * The real subject (BookingService) that performs actual operations.
     */
    private BookingService $bookingService;

    /**
     * Constructor - Dependency Injection of BookingService
     * 
     * @param BookingService $bookingService The real service to delegate to
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * List bookings - Accessible by admin and students.
     * 
     * @param array $filters Optional filters
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     * @throws AccessDeniedHttpException If user is not authorized
     */
    public function listBookings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        // Allow access for admin and student roles
        if ($this->canViewBookingList()) {
            return $this->bookingService->listBookings($filters, $perPage);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Get booking details - Accessible by admin and student leaders.
     * 
     * @param int $bookingId Booking ID
     * @return FacilityBooking
     * @throws AccessDeniedHttpException If user is not authorized
     */
    public function getBookingDetails(int $bookingId): FacilityBooking
    {
        // Allow access for admin and student leaders
        if ($this->canViewBookingDetails()) {
            return $this->bookingService->getBookingDetails($bookingId);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Create a booking - Admin only
     * 
     * @param array $data Booking data
     * @return FacilityBooking
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function createBooking(array $data): FacilityBooking
    {
        // Only Admin can create bookings
        if ($this->isAdmin()) {
            return $this->bookingService->createBooking($data);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Update a booking - Admin only
     * 
     * @param int $bookingId Booking ID
     * @param array $data Updated data
     * @return FacilityBooking
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function updateBooking(int $bookingId, array $data): FacilityBooking
    {
        // Only Admin can update bookings
        if ($this->isAdmin()) {
            return $this->bookingService->updateBooking($bookingId, $data);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Cancel a booking - Admin only
     * 
     * @param int $bookingId Booking ID
     * @return FacilityBooking
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function cancelBooking(int $bookingId): FacilityBooking
    {
        // Only Admin can cancel bookings
        if ($this->isAdmin()) {
            return $this->bookingService->cancelBooking($bookingId);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Create a pending booking from event (for event-based workflows).
     * Admin or authorized roles only.
     * 
     * @param int $eventId Event ID
     * @param int $facilityId Facility ID
     * @param int|null $createdBy User ID
     * @return FacilityBooking
     */
    public function createPendingBookingFromEvent(
        int $eventId,
        int $facilityId,
        ?int $createdBy = null
    ): FacilityBooking {
        if ($this->isAdmin()) {
            return $this->bookingService->createPendingBookingFromEvent($eventId, $facilityId, $createdBy);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Create a pending booking with direct start/end times.
     * Admin or authorized roles only.
     * 
     * @param int $eventId Event ID
     * @param int $facilityId Facility ID
     * @param string $startAt Start time
     * @param string $endAt End time
     * @param int|null $createdBy User ID
     * @return FacilityBooking
     */
    public function createPendingBooking(
        int $eventId,
        int $facilityId,
        string $startAt,
        string $endAt,
        ?int $createdBy = null
    ): FacilityBooking {
        if ($this->isAdmin()) {
            return $this->bookingService->createPendingBooking($eventId, $facilityId, $startAt, $endAt, $createdBy);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Approve a booking for an event.
     * Admin only.
     * 
     * @param int $eventId Event ID
     * @param int|null $approvedBy User ID
     * @return FacilityBooking
     */
    public function approveBookingForEvent(int $eventId, ?int $approvedBy = null): FacilityBooking
    {
        if ($this->isAdmin()) {
            return $this->bookingService->approveBookingForEvent($eventId, $approvedBy);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Reject a booking for an event.
     * Admin only.
     * 
     * @param int $eventId Event ID
     * @param string|null $reason Rejection reason
     * @param int|null $rejectedBy User ID
     * @return FacilityBooking
     */
    public function rejectBookingForEvent(
        int $eventId,
        ?string $reason = null,
        ?int $rejectedBy = null
    ): FacilityBooking {
        if ($this->isAdmin()) {
            return $this->bookingService->rejectBookingForEvent($eventId, $reason, $rejectedBy);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Check if the current user is an Admin.
     * 
     * @return bool
     */
    private function isAdmin(?User $user = null): bool
    {
        $user = $user ?? Auth::user();
        return $user && $user->role === 'admin';
    }

    /**
     * Check if the current user is a Student.
     */
    private function isStudent(?User $user = null): bool
    {
        $user = $user ?? Auth::user();
        return $user && $user->role === 'student';
    }

    /**
     * Public method to check if current user is admin (used by controllers).
     * 
     * @return bool
     */
    public function isUserAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if the current user can view bookings list.
     */
    private function canViewBookingList(): bool
    {
        return $this->isAdmin() || $this->isStudent();
    }

    /**
     * Check if the current user can view booking details.
     */
    private function canViewBookingDetails(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user) && $this->hasLeadershipPosition($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the current student holds a leadership position.
     */
    private function hasLeadershipPosition(User $user): bool
    {
        if (in_array($user->position ?? null, ['president', 'committee'], true)) {
            return true;
        }

        return method_exists($user, 'societyMemberships')
            && $user->societyMemberships()
                ->where('status', 'active')
                ->whereIn('position', ['president', 'committee'])
                ->exists();
    }
}
