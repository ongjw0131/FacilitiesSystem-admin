<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityStoreRequest;
use App\Http\Requests\FacilityUpdateRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\User;
use App\Services\Facility\FacilityProxy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * FacilityController - Client in Proxy Pattern
 * 
 * This controller interacts ONLY with FacilityProxy, never directly with FacilityService.
 * The proxy handles all access control, keeping this controller clean and focused on HTTP concerns.
 */
class FacilityController extends Controller
{
    /**
     * The Proxy that controls access to FacilityService
     */
    private FacilityProxy $facilityProxy;

    /**
     * Constructor - Inject FacilityProxy (Proxy Pattern)
     * 
     * @param FacilityProxy $facilityProxy The proxy that controls facility operations
     */
    public function __construct(FacilityProxy $facilityProxy)
    {
        $this->facilityProxy = $facilityProxy;
    }
    /**
     * Display a listing of the resource.
     * Uses Proxy Pattern - delegates to FacilityProxy which controls access.
     */
    public function index(): View
    {
        // Proxy Pattern: Controller calls proxy, not the real service
        // Proxy checks permissions and delegates to FacilityService
        $facilities = $this->facilityProxy->getFacilityList(10);

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     * Proxy verifies admin access to block non-admin from accessing UI.
     * Students (including those with president/committee positions) cannot access this.
     */
    public function create(): View
    {
        // Proxy Pattern: Block non-admin from accessing create form
        // This prevents all students (including those with president/committee positions) from seeing the UI
        if (!$this->facilityProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     * Uses Proxy Pattern - delegates to FacilityProxy for access control.
     */
    public function store(FacilityStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!$this->facilityProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        if ($request->hasFile('facility_image')) {
            $data['facility_image_path'] = Storage::disk('public')->putFile('facilities', $request->file('facility_image'));
        }

        try {
            // Proxy Pattern: Proxy checks if user is Admin before creating
            $this->facilityProxy->createFacility($data);
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['venue_prefix' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    /**
     * Display the specified resource.
     * Uses Proxy Pattern - proxy controls view access.
     */
    public function show(Facility $facility): View
    {
        $this->abortIfCannotViewFacilityDetails();

        // Proxy Pattern: Verify user can view this facility
        $this->facilityProxy->getFacilityById($facility->id);

        $tz = config('app.timezone', 'Asia/Kuala_Lumpur');
        $selectedDateInput = request('date') ?? Carbon::now($tz)->toDateString();
        $selectedDate = Carbon::parse($selectedDateInput, $tz)->startOfDay();

        $dayStart = $selectedDate->copy()->setTime(8, 0);
        $dayEnd = $selectedDate->copy()->setTime(22, 0);

        $facilities = Facility::where('name', $facility->name)
            ->where('type', $facility->type)
            ->where('location', $facility->location)
            ->orderBy('id')
            ->with(['facilityBookings' => function ($q) use ($dayStart, $dayEnd) {
                $q->where('start_at', '<', $dayEnd)
                    ->where('end_at', '>', $dayStart);
            }])->get();

        $timeSlots = $this->generateTimeSlots($dayStart, $dayEnd);
        $slotStates = $this->mapSlotStates($facilities, $timeSlots);

        return view('admin.facilities.show', [
            'facility' => $facility,
            'facilities' => $facilities,
            'timeSlots' => $timeSlots,
            'slotStates' => $slotStates,
            'selectedDate' => $selectedDate,
            'selectedDateInput' => $selectedDateInput,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Proxy verifies admin access to block non-admin from accessing UI.
     * Students (including those with president/committee positions) cannot access this.
     */
    public function edit(Facility $facility): View
    {
        // Proxy Pattern: Block non-admin from accessing edit form
        if (!$this->facilityProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        $venueCount = Facility::where('name', $facility->name)
            ->where('type', $facility->type)
            ->where('location', $facility->location)
            ->count();

        return view('admin.facilities.edit', [
            'facility' => $facility,
            'venueCount' => $venueCount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Uses Proxy Pattern - delegates to FacilityProxy for access control.
     */
    public function update(FacilityUpdateRequest $request, Facility $facility): RedirectResponse
    {
        $data = $request->validated();
        
        if (!$this->facilityProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        if ($request->hasFile('facility_image')) {
            if ($facility->facility_image_path) {
                Storage::disk('public')->delete($facility->facility_image_path);
            }

            $data['facility_image_path'] = Storage::disk('public')->putFile('facilities', $request->file('facility_image'));
        }

        // Proxy Pattern: Proxy checks if user is Admin before updating
        $this->facilityProxy->updateFacility($facility->id, $data);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Uses Proxy Pattern - delegates to FacilityProxy for access control.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        if (!$this->facilityProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Proxy Pattern: Proxy checks if user is Admin before deactivating
        $this->facilityProxy->deactivateFacility($facility->id);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility deactivated.');
    }

    /**
     * Abort when a user is not permitted to view facility details.
     */
    private function abortIfCannotViewFacilityDetails(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'student' && $this->userHasLeadershipPosition($user)) {
            return;
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Determine if a student has a leadership position (president/committee).
     */
    private function userHasLeadershipPosition(User $user): bool
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

    /**
     * Build 30-minute time slots between start and end.
     *
     * @return array<int, array{start: Carbon, end: Carbon, label: string}>
     */
    private function generateTimeSlots(Carbon $start, Carbon $end): array
    {
        $slots = [];
        $cursor = $start->copy();

        while ($cursor < $end) {
            $slotEnd = $cursor->copy()->addMinutes(30);
            $slots[] = [
                'start' => $cursor->copy(),
                'end' => $slotEnd,
                'label' => $cursor->format('H:i'),
            ];
            $cursor = $slotEnd;
        }

        return $slots;
    }

    /**
     * Map facilities to slot states (available/booked/inactive) and related bookings.
     *
     * @param Collection<int, Facility> $facilities
     * @param array<int, array{start: Carbon, end: Carbon, label: string}> $timeSlots
     * @return array<int, array{states: array<int, string>, bookings: array<int, FacilityBooking|null>}>
     */
    private function mapSlotStates(Collection $facilities, array $timeSlots): array
    {
        $result = [];
        $slotCount = count($timeSlots);

        foreach ($facilities as $facility) {
            $states = array_fill(0, $slotCount, 'available');
            $bookings = array_fill(0, $slotCount, null);

            foreach ($facility->facilityBookings as $booking) {
                $isBooked = in_array($booking->status, FacilityBooking::BLOCKING_STATUSES, true);

                foreach ($timeSlots as $index => $slot) {
                    $overlaps = $booking->start_at < $slot['end'] && $booking->end_at > $slot['start'];
                    if ($overlaps && $isBooked) {
                        $states[$index] = 'booked';
                        $bookings[$index] = $booking;
                    }
                }
            }

            if (!$facility->is_active) {
                foreach ($states as $i => $state) {
                    if ($state !== 'booked') {
                        $states[$i] = 'inactive';
                    }
                }
            }

            $result[$facility->id] = [
                'states' => $states,
                'bookings' => $bookings,
            ];
        }

        return $result;
    }
}
