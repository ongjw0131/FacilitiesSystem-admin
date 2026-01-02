<?php

namespace App\Facades;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketOrder;
use App\Services\EventImageService;
use App\Services\EventValidationService;
use App\Services\FacilityBookingService;
use App\Services\TicketService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Event Facade
 * 
 * Provides a simplified interface to the complex event management subsystem.
 * This facade coordinates between multiple services:
 * - Event creation and management
 * - Image handling
 * - Ticket management
 * - Facility bookings
 * - Payment processing
 */
class EventFacade
{
    protected EventImageService $imageService;
    protected EventValidationService $validationService;
    protected FacilityBookingService $facilityService;
    protected TicketService $ticketService;
    protected PaymentService $paymentService;

    public function __construct(
        EventImageService $imageService,
        EventValidationService $validationService,
        FacilityBookingService $facilityService,
        TicketService $ticketService,
        PaymentService $paymentService
    ) {
        $this->imageService = $imageService;
        $this->validationService = $validationService;
        $this->facilityService = $facilityService;
        $this->ticketService = $ticketService;
        $this->paymentService = $paymentService;
    }

    /**
     * Get filtered events list
     */
    public function getEvents(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Event::query();

        // Exclude deleted events
        $query->where(function ($q) {
            $q->where('is_deleted', 0)->orWhereNull('is_deleted');
        });

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('start_date', 'asc')->get();
    }

    /**
     * Create a new event with all related data
     */
    public function createEvent(Request $request, array $eventData, bool $isAdmin = false): Event
    {
        // Validate the request
        $validatedData = $this->validationService->validateEventCreation($request, $isAdmin);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $validatedData['image_url_path'] = $this->imageService->uploadEventImage($request->file('image'));
            }

            // Set default values
            $validatedData['organizer_id'] = $request->user()?->id ?? null;
            $validatedData['is_deleted'] = 0;

            // Create the event
            $event = Event::create($validatedData);

            // Link event to society if society_id is provided
            if (!empty($eventData['society_id'])) {
                $event->societies()->attach($eventData['society_id']);
            }

            // Handle tickets if provided
            if ($isAdmin && isset($validatedData['tickets'])) {
                $this->ticketService->createTicketsForEvent($event, $validatedData['tickets'], $request->user());
            }

            // Handle facility booking if needed
            if (!empty($validatedData['needs_facility']) && !empty($validatedData['facility_id'])) {
                $this->facilityService->createBooking($event, $validatedData, $request->user());
            }

            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing event
     */
    public function updateEvent(Event $event, Request $request, array $eventData): Event
    {
        // Validate the request
        $validatedData = $this->validationService->validateEventUpdate($request);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validatedData['image_url_path'] = $this->imageService->uploadEventImage($request->file('image'));
        }

        $event->fill($validatedData);
        $event->save();

        return $event;
    }

    /**
     * Get event details with related data
     */
    public function getEventDetails(Event $event, ?int $userId = null): array
    {
        if ($event->is_deleted) {
            abort(404);
        }

        // Get available tickets
        $tickets = $this->ticketService->getAvailableTickets($event);

        // Check if user has joined - only if event_user table exists
        $hasJoined = false;
        // Commenting out until event_user table is created
        // if ($userId) {
        //     $hasJoined = $event->attendees()->where('user_id', $userId)->exists();
        // }

        return [
            'event' => $event,
            'tickets' => $tickets,
            'hasJoined' => $hasJoined,
        ];
    }

    /**
     * Get admin event details with comprehensive data
     */
    public function getAdminEventDetails(Event $event): array
    {
        if ($event->is_deleted) {
            abort(404);
        }

        // Get facility bookings
        $bookings = $this->facilityService->getEventBookings($event);

        // Get all tickets
        $tickets = $this->ticketService->getAllTicketsForEvent($event);

        // Get ticket orders
        $ticketOrders = $this->ticketService->getEventOrders($event);

        // Calculate attendees count
        $attendeesCount = $this->ticketService->getEventAttendeesCount($event);

        return [
            'event' => $event,
            'bookings' => $bookings,
            'tickets' => $tickets,
            'ticketOrders' => $ticketOrders,
            'attendeesCount' => $attendeesCount,
        ];
    }

    /**
     * Process payment success callback
     */
    public function handlePaymentSuccess(Event $event, string $sessionId): bool
    {
        return $this->paymentService->processSuccessfulPayment($sessionId, $event);
    }

    /**
     * Soft delete an event
     */
    public function softDeleteEvent(Event $event): bool
    {
        $event->is_deleted = 1;
        return $event->save();
    }

    /**
     * Restore a soft-deleted event
     */
    public function restoreEvent(Event $event): bool
    {
        $event->is_deleted = 0;
        return $event->save();
    }

    /**
     * Get event with tickets and booking details
     */
    public function getEventForEdit(Event $event): array
    {
        $facilities = $this->facilityService->getActiveFacilities();

        return [
            'event' => $event,
            'facilities' => $facilities,
        ];
    }

/**
 * Get the ticket service (for direct access when needed)
 */
public function getTicketService(): TicketService
{
    return $this->ticketService;
}

    /**
     * Purchase tickets for an event
     */
    public function purchaseTickets(Event $event, int $ticketId, int $quantity, $user): array
    {
        return $this->ticketService->createTicketOrder($event, $ticketId, $quantity, $user);
    }
}