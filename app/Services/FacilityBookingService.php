<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Facility Booking Service
 * 
 * Handles facility booking operations for events
 */
class FacilityBookingService
{
    /**
     * Create a facility booking for an event
     */
    public function createBooking(Event $event, array $data, $user): bool
    {
        if (!Schema::hasTable('facility_bookings')) {
            return false;
        }

        DB::table('facility_bookings')->insert([
            'event_id' => $event->id,
            'facility_id' => $data['facility_id'],
            'start_at' => $data['facility_start_at'] ?? null,
            'end_at' => $data['facility_end_at'] ?? null,
            'status' => 'pending',
            'reject_reason' => null,
            'created_by' => $user?->id ?? null,
            'approved_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    /**
     * Get all facility bookings for an event
     */
    public function getEventBookings(Event $event)
    {
        if (!Schema::hasTable('facility_bookings')) {
            return collect([]);
        }

        return DB::table('facility_bookings')
            ->join('facilities', 'facility_bookings.facility_id', '=', 'facilities.id')
            ->where('facility_bookings.event_id', $event->id)
            ->select(
                'facility_bookings.*',
                'facilities.name as facility_name',
                'facilities.location as facility_location'
            )
            ->get();
    }

    /**
     * Get all active facilities
     */
    public function getActiveFacilities()
    {
        if (!Schema::hasTable('facilities')) {
            return collect([]);
        }

        return DB::table('facilities')->where('is_active', 1)->get();
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus(int $bookingId, string $status, ?int $approvedBy = null, ?string $rejectReason = null): bool
    {
        if (!Schema::hasTable('facility_bookings')) {
            return false;
        }

        $updateData = [
            'status' => $status,
            'updated_at' => now(),
        ];

        if ($approvedBy) {
            $updateData['approved_by'] = $approvedBy;
        }

        if ($rejectReason) {
            $updateData['reject_reason'] = $rejectReason;
        }

        return DB::table('facility_bookings')
            ->where('id', $bookingId)
            ->update($updateData) > 0;
    }
}