<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FacilityBooking;
use App\Services\Facility\AvailabilityService;
use App\Services\Facility\BookingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class FacilityAvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly BookingService $bookingService
    ) {
    }

    /**
     * POST /api/facilities/availability
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'requestID' => ['nullable', 'string'],
            'facilityId' => ['required', 'exists:facilities,id'],
            'startAt' => ['required', 'date'],
            'endAt' => ['required', 'date', 'after:startAt'],
            'exclude_booking_id' => ['nullable', 'integer', 'exists:facility_bookings,id'],
        ]);

        $requestId = $this->requestId($request, $validated['requestID'] ?? null);
        $tz = config('app.timezone', 'Asia/Kuala_Lumpur');

        if (!$this->availabilityService->isFacilityActive((int) $validated['facilityId'])) {
            return $this->failure($requestId, 'Facility is inactive.');
        }

        $available = $this->availabilityService->isAvailable(
            (int) $validated['facilityId'],
            Carbon::parse($validated['startAt'], $tz),
            Carbon::parse($validated['endAt'], $tz),
            $validated['exclude_booking_id'] ?? null
        );

        $conflicts = $available
            ? []
            : $this->availabilityService->getConflicts(
                (int) $validated['facilityId'],
                Carbon::parse($validated['startAt'], $tz),
                Carbon::parse($validated['endAt'], $tz),
                $validated['exclude_booking_id'] ?? null
            )->map(function (FacilityBooking $booking) {
                return [
                    'id' => $booking->id,
                    'event_id' => $booking->event_id,
                    'start_at' => $booking->start_at,
                    'end_at' => $booking->end_at,
                    'status' => $booking->status,
                ];
            })->values();

        return $this->success($requestId, [
            'isAvailable' => $available,
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * POST /api/facility-bookings/pending
     */
    public function createPendingBooking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'requestID' => ['nullable', 'string'],
            'eventId' => ['required', 'integer'],
            'facilityId' => ['required', 'exists:facilities,id'],
            'startAt' => ['required', 'date'],
            'endAt' => ['required', 'date', 'after:startAt'],
        ]);

        $requestId = $this->requestId($request, $validated['requestID'] ?? null);

        try {
            $booking = $this->bookingService->createPendingBooking(
                (int) $validated['eventId'],
                (int) $validated['facilityId'],
                $validated['startAt'],
                $validated['endAt'],
                $request->user()?->id
            );

            return $this->success($requestId, [
                'bookingId' => $booking->id,
            ]);
        } catch (\Throwable $e) {
            return $this->failure($requestId, $e->getMessage());
        }
    }

    /**
     * POST /api/facility-bookings/{eventId}/approve
     */
    public function approveBooking(Request $request, int $eventId): JsonResponse
    {
        $validated = $request->validate([
            'requestID' => ['nullable', 'string'],
            'approvedBy' => ['nullable', 'integer'],
        ]);

        $requestId = $this->requestId($request, $validated['requestID'] ?? null);

        try {
            $booking = FacilityBooking::where('event_id', $eventId)
                ->latest('start_at')
                ->firstOrFail();

            $this->authorize('approve', $booking);

            $booking = $this->bookingService->approveBookingForEvent($eventId, $validated['approvedBy'] ?? $request->user()?->id);

            return $this->success($requestId, []);
        } catch (ModelNotFoundException $e) {
            return $this->failure($requestId, 'Facility booking not found for this event.')
                ->setStatusCode(404);
        } catch (AuthorizationException $e) {
            return $this->failure($requestId, 'Unauthorized', ['code' => 403])
                ->setStatusCode(403);
        } catch (\Throwable $e) {
            return $this->failure($requestId, $e->getMessage());
        }
    }

    /**
     * POST /api/facility-bookings/{eventId}/reject
     */
    public function rejectBooking(Request $request, int $eventId): JsonResponse
    {
        $validated = $request->validate([
            'requestID' => ['nullable', 'string'],
            'reason' => ['nullable', 'string'],
            'rejectedBy' => ['nullable', 'integer'],
        ]);

        $requestId = $this->requestId($request, $validated['requestID'] ?? null);

        try {
            $booking = FacilityBooking::where('event_id', $eventId)
                ->latest('start_at')
                ->firstOrFail();

            $this->authorize('reject', $booking);

            $booking = $this->bookingService->rejectBookingForEvent(
                $eventId,
                $validated['reason'] ?? null,
                $validated['rejectedBy'] ?? $request->user()?->id
            );

            return $this->success($requestId, []);
        } catch (ModelNotFoundException $e) {
            return $this->failure($requestId, 'Facility booking not found for this event.')
                ->setStatusCode(404);
        } catch (AuthorizationException $e) {
            return $this->failure($requestId, 'Unauthorized', ['code' => 403])
                ->setStatusCode(403);
        } catch (\Throwable $e) {
            return $this->failure($requestId, $e->getMessage());
        }
    }

    private function success(string $requestId, array $data, string $message = ''): JsonResponse
    {
        return response()->json([
            'requestID' => $requestId,
            'status' => 'S',
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ...$data,
        ]);
    }

    private function failure(string $requestId, string $message, array $errors = []): JsonResponse
    {
        return response()->json([
            'requestID' => $requestId,
            'status' => 'F',
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    private function requestId(Request $request, ?string $incoming = null): string
    {
        return $incoming ?? $request->header('X-Request-ID') ?? Str::uuid()->toString();
    }
}
