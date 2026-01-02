<?php

namespace App\Services\Facility;

use App\Models\Event;
use App\Models\Facility;
use App\Models\FacilityBooking;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * BookingService - Real Subject in Proxy Pattern
 * 
 * This class contains the actual business logic for booking operations.
 * It does NOT perform any role or permission checking.
 * Access control is delegated to the BookingProxy.
 */
class BookingService
{
    public function __construct(
        private readonly AvailabilityService $availabilityService
    ) {
    }

    /**
     * Create a pending booking using the event's start/end time pulled from the Event API.
     */
    public function createPendingBookingFromEvent(
        int $eventId,
        int $facilityId,
        ?int $createdBy = null
    ): FacilityBooking {
        $event = $this->fetchEvent($eventId);

        $startAt = $event['start_at'] ?? null;
        $endAt = $event['end_at'] ?? null;

        if (!$startAt || !$endAt || !Overlap::startsBeforeEnds($startAt, $endAt)) {
            throw new RuntimeException('Event start/end time is invalid.');
        }

        $facility = Facility::find($facilityId);

        if (!$facility) {
            throw new RuntimeException('Facility not found.');
        }

        if (!$facility->is_active) {
            throw new RuntimeException('Facility is inactive.');
        }

        if (!$this->availabilityService->isAvailable($facilityId, $startAt, $endAt)) {
            throw new RuntimeException('Facility is not available for the selected time range.');
        }

        return FacilityBooking::create([
            'event_id' => $eventId,
            'facility_id' => $facilityId,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => FacilityBooking::STATUS_PENDING,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Create a pending booking using direct start/end from request.
     */
    public function createPendingBooking(
        int $eventId,
        int $facilityId,
        string $startAt,
        string $endAt,
        ?int $createdBy = null
    ): FacilityBooking {
        $start = Carbon::parse($startAt, config('app.timezone'));
        $end = Carbon::parse($endAt, config('app.timezone'));

        if (!Overlap::startsBeforeEnds($start, $end)) {
            throw new RuntimeException('Start time must be before end time.');
        }

        $facility = Facility::find($facilityId);

        if (!$facility) {
            throw new RuntimeException('Facility not found.');
        }

        if (!$facility->is_active) {
            throw new RuntimeException('Facility is inactive.');
        }

        if (!$this->availabilityService->isAvailable($facilityId, $start, $end)) {
            throw new RuntimeException('Facility is not available for the selected time range.');
        }

        return FacilityBooking::create([
            'event_id' => $eventId,
            'facility_id' => $facilityId,
            'start_at' => $start,
            'end_at' => $end,
            'status' => FacilityBooking::STATUS_PENDING,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Approve an event's booking after re-validating event status and availability.
     */
    public function approveBookingForEvent(int $eventId, ?int $approvedBy = null): FacilityBooking
    {
        $booking = $this->getBookingForEvent($eventId);
        $event = $this->fetchEvent($eventId);

        if (strtoupper((string) ($event['status'] ?? '')) !== FacilityBooking::STATUS_APPROVED) {
            throw new RuntimeException('Event is not approved yet.');
        }

        $isAvailable = $this->availabilityService->isAvailable(
            $booking->facility_id,
            $booking->start_at,
            $booking->end_at,
            $booking->id
        );

        if (!$isAvailable) {
            throw new RuntimeException('Facility is no longer available for approval.');
        }

        $booking->update([
            'status' => FacilityBooking::STATUS_APPROVED,
            'approved_by' => $approvedBy,
            'reject_reason' => null,
        ]);

        return $booking->fresh(['facility', 'event']);
    }

    /**
     * Reject an event's booking with a reason.
     */
    public function rejectBookingForEvent(
        int $eventId,
        ?string $reason = null,
        ?int $rejectedBy = null
    ): FacilityBooking {
        $booking = $this->getBookingForEvent($eventId);

        $booking->update([
            'status' => FacilityBooking::STATUS_REJECTED,
            'reject_reason' => $reason,
            'approved_by' => $rejectedBy,
        ]);

        return $booking->fresh(['facility', 'event']);
    }

    /**
     * Fetch event data from Event API; fallback to local DB if API unavailable.
     */
    public function fetchEvent(int $eventId): array
    {
        $baseUrl = config('services.event.base_url');

        if ($baseUrl) {
            try {
                $response = Http::baseUrl($baseUrl)
                    ->acceptJson()
                    ->timeout(5)
                    ->get("/api/events/{$eventId}");

                if ($response->successful()) {
                    $payload = $response->json();
                    $event = $payload['event'] ?? $payload['data'] ?? $payload;

                    return [
                        'id' => $event['id'] ?? $eventId,
                        'start_at' => $event['start_at'] ?? $event['startAt'] ?? $event['start_date'] ?? null,
                        'end_at' => $event['end_at'] ?? $event['endAt'] ?? $event['end_date'] ?? null,
                        'status' => $event['status'] ?? null,
                        'raw' => $event,
                    ];
                }
            } catch (\Throwable $e) {
                // Fall back to DB if API call fails
            }
        }

        $localEvent = Event::find($eventId);

        if ($localEvent) {
            return [
                'id' => $localEvent->id,
                'start_at' => $localEvent->start_date,
                'end_at' => $localEvent->end_date,
                'status' => $localEvent->status,
                'raw' => $localEvent->toArray(),
            ];
        }

        throw new RuntimeException('Event not found.');
    }

    private function getBookingForEvent(int $eventId): FacilityBooking
    {
        $booking = FacilityBooking::where('event_id', $eventId)
            ->latest('start_at')
            ->first();

        if (!$booking) {
            throw new RuntimeException('Facility booking not found for this event.');
        }

        return $booking;
    }

    /**
     * Create a booking (admin manual booking management).
     * 
     * @param array $data Booking data
     * @return FacilityBooking
     */
    public function createBooking(array $data): FacilityBooking
    {
        $facilityId = $data['facility_id'];
        $startAt = $data['start_at'];
        $endAt = $data['end_at'];
        $status = $data['status'] ?? FacilityBooking::STATUS_PENDING;

        $facility = Facility::findOrFail($facilityId);

        if (!$facility->is_active) {
            throw new RuntimeException('Facility is inactive and cannot be booked.');
        }

        if (in_array($status, FacilityBooking::BLOCKING_STATUSES, true)) {
            if (!$this->availabilityService->isAvailable($facilityId, $startAt, $endAt)) {
                throw new RuntimeException('Facility is not available for the selected time range.');
            }
        }

        return FacilityBooking::create($data);
    }

    /**
     * Update an existing booking (admin manual booking management).
     * 
     * @param int $bookingId Booking ID to update
     * @param array $data Updated booking data
     * @return FacilityBooking
     */
    public function updateBooking(int $bookingId, array $data): FacilityBooking
    {
        $booking = FacilityBooking::findOrFail($bookingId);

        $facilityId = $data['facility_id'] ?? $booking->facility_id;
        $startAt = $data['start_at'] ?? $booking->start_at;
        $endAt = $data['end_at'] ?? $booking->end_at;
        $status = $data['status'] ?? $booking->status;

        $facility = Facility::withTrashed()->findOrFail($facilityId);

        if (in_array($status, FacilityBooking::BLOCKING_STATUSES, true)) {
            if ($facility->trashed() || !$facility->is_active) {
                throw new RuntimeException('Facility is inactive and cannot be booked.');
            }

            if (!$this->availabilityService->isAvailable($facilityId, $startAt, $endAt, $bookingId)) {
                throw new RuntimeException('Facility is not available for the selected time range.');
            }
        }

        $booking->update($data);

        return $booking->fresh();
    }

    /**
     * Cancel a booking (admin manual booking management).
     * 
     * @param int $bookingId Booking ID to cancel
     * @return FacilityBooking
     */
    public function cancelBooking(int $bookingId): FacilityBooking
    {
        $booking = FacilityBooking::findOrFail($bookingId);
        
        $booking->update(['status' => FacilityBooking::STATUS_CANCELLED]);

        return $booking->fresh();
    }

    /**
     * List bookings with optional filters.
     * 
     * @param array $filters Optional filters (date, status, etc.)
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function listBookings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = FacilityBooking::with(['facility', 'event']);

        // Apply date filter if provided
        if (isset($filters['date'])) {
            $tz = config('app.timezone', 'Asia/Kuala_Lumpur');
            $dayStart = Carbon::parse($filters['date'], $tz)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();

            $query->where('start_at', '<', $dayEnd)
                  ->where('end_at', '>', $dayStart);
        }

        // Apply status filter if provided
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('start_at')->paginate($perPage);
    }

    /**
     * Get booking details by ID.
     * 
     * @param int $bookingId Booking ID
     * @return FacilityBooking
     */
    public function getBookingDetails(int $bookingId): FacilityBooking
    {
        return FacilityBooking::with(['facility', 'event', 'creator', 'approver'])
            ->findOrFail($bookingId);
    }
}
