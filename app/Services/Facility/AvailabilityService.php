<?php

namespace App\Services\Facility;

use App\Models\Facility;
use App\Models\FacilityBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    /**
     * Check if a facility is available for the given time range.
     * 
     * Two ranges overlap if: startA < endB AND endA > startB
     * 
     * @param int $facilityId
     * @param Carbon|string $startAt
     * @param Carbon|string $endAt
     * @param int|null $excludeBookingId Optional booking ID to exclude (for updates)
     * @return bool True if available, false if there's an overlap
     */
    public function isAvailable(
        int $facilityId,
        $startAt,
        $endAt,
        ?int $excludeBookingId = null
    ): bool {
        $startAt = $this->toAppTimezone($startAt);
        $endAt = $this->toAppTimezone($endAt);

        // Check if the facility is active
        $facility = Facility::find($facilityId);
        if (!$facility || !$facility->is_active) {
            return false;
        }

        // Find overlapping bookings
        // Overlap rule: start_at < $endAt AND end_at > $startAt
        $query = FacilityBooking::where('facility_id', $facilityId)
            ->whereIn('status', FacilityBooking::BLOCKING_STATUSES)
            ->where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt);

        // Exclude current booking when updating
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $overlappingCount = $query->count();

        return $overlappingCount === 0;
    }

    /**
     * Get all overlapping bookings for a given facility and time range.
     * 
     * @param int $facilityId
     * @param Carbon|string $startAt
     * @param Carbon|string $endAt
     * @param int|null $excludeBookingId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConflicts(
        int $facilityId,
        $startAt,
        $endAt,
        ?int $excludeBookingId = null
    ) {
        $startAt = $this->toAppTimezone($startAt);
        $endAt = $this->toAppTimezone($endAt);

        $query = FacilityBooking::where('facility_id', $facilityId)
            ->whereIn('status', FacilityBooking::BLOCKING_STATUSES)
            ->where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt)
            ->with(['event', 'facility', 'creator']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    /**
     * Create a booking with availability check inside a database transaction.
     * 
     * @param array $bookingData
     * @return FacilityBooking
     * @throws \Exception If facility is not available
     */
    public function createBookingWithAvailabilityCheck(array $bookingData): FacilityBooking
    {
        return DB::transaction(function () use ($bookingData) {
            // Check availability inside transaction to reduce race conditions
            $isAvailable = $this->isAvailable(
                $bookingData['facility_id'],
                $bookingData['start_at'],
                $bookingData['end_at']
            );

            if (!$isAvailable) {
                throw new \Exception('Facility is not available for the selected time range.');
            }

            return FacilityBooking::create($bookingData);
        });
    }

    /**
     * Update a booking with availability check inside a database transaction.
     * 
     * @param FacilityBooking $booking
     * @param array $updateData
     * @return FacilityBooking
     * @throws \Exception If facility is not available
     */
    public function updateBookingWithAvailabilityCheck(
        FacilityBooking $booking,
        array $updateData
    ): FacilityBooking {
        return DB::transaction(function () use ($booking, $updateData) {
            // Only check if time or facility changed
            $needsAvailabilityCheck = 
                (isset($updateData['facility_id']) && $updateData['facility_id'] != $booking->facility_id) ||
                (isset($updateData['start_at']) && $updateData['start_at'] != $booking->start_at) ||
                (isset($updateData['end_at']) && $updateData['end_at'] != $booking->end_at);

            if ($needsAvailabilityCheck) {
                $facilityId = $updateData['facility_id'] ?? $booking->facility_id;
                $startAt = $updateData['start_at'] ?? $booking->start_at;
                $endAt = $updateData['end_at'] ?? $booking->end_at;

                $isAvailable = $this->isAvailable(
                    $facilityId,
                    $startAt,
                    $endAt,
                    $booking->id // Exclude current booking
                );

                if (!$isAvailable) {
                    throw new \Exception('Facility is not available for the selected time range.');
                }
            }

            $booking->update($updateData);
            return $booking->fresh();
        });
    }

    /**
     * Validate that a facility exists and is active.
     * 
     * @param int $facilityId
     * @return bool
     */
    public function isFacilityActive(int $facilityId): bool
    {
        $facility = Facility::find($facilityId);
        return $facility && $facility->is_active;
    }

    /**
     * Normalize date/time input to Carbon instance in app timezone.
     */
    private function toAppTimezone(Carbon|string $value): Carbon
    {
        $date = $value instanceof Carbon ? $value->copy() : Carbon::parse($value);
        return $date->timezone(config('app.timezone'));
    }
}
