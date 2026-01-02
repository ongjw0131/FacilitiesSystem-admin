<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\User;
use App\Services\Facility\BookingProxy;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * FacilityBookingController - Client in Proxy Pattern
 * 
 * This controller interacts ONLY with BookingProxy, never directly with BookingService.
 * The proxy handles all access control, keeping this controller clean and focused on HTTP concerns.
 */
class FacilityBookingController extends Controller
{
    /**
     * The Proxy that controls access to BookingService
     */
    private BookingProxy $bookingProxy;

    /**
     * Constructor - Inject BookingProxy (Proxy Pattern)
     * 
     * @param BookingProxy $bookingProxy The proxy that controls booking operations
     */
    public function __construct(BookingProxy $bookingProxy)
    {
        $this->bookingProxy = $bookingProxy;
    }

    /**
     * Display a listing of the resource.
     * Uses Proxy Pattern - delegates to BookingProxy which controls access.
     */
    public function index(Request $request): View
    {
        $tz = config('app.timezone', 'Asia/Kuala_Lumpur');
        $selectedDateInput = $request->input('date', Carbon::now($tz)->toDateString());

        // Proxy Pattern: Controller calls proxy, not the real service
        // Proxy checks permissions and delegates to BookingService
        $bookings = $this->bookingProxy->listBookings([
            'date' => $selectedDateInput,
        ], 15);

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'selectedDateInput' => $selectedDateInput,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Proxy will verify admin access when store() is called.
     * Students (including those with president/committee positions) cannot access this.
     */
    public function create(): View
    {
        // Proxy Pattern: Block non-admin from accessing create form
        // This prevents all students (including those with president/committee positions) from seeing the UI
        if (!$this->bookingProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        $facilityGroups = Facility::where('is_active', true)
            ->select('name', 'type', 'location', DB::raw('MIN(id) as representative_id'))
            ->groupBy('name', 'type', 'location')
            ->orderBy('name')
            ->get();

        return view('admin.bookings.create', [
            'facilityGroups' => $facilityGroups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Uses Proxy Pattern - delegates to BookingProxy for access control.
     */
    public function store(BookingStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? FacilityBooking::STATUS_PENDING;
        $data['created_by'] = $request->user()?->id;

        if (!$this->bookingProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        $facility = Facility::findOrFail($data['facility_id']);

        if ($facility->name !== $request->input('facility_name')) {
            return back()
                ->withErrors(['facility_id' => 'Selected venue does not belong to the chosen facility.'])
                ->withInput();
        }

        if (in_array($data['status'], [FacilityBooking::STATUS_APPROVED, FacilityBooking::STATUS_REJECTED], true)) {
            $data['approved_by'] = $request->user()?->id;
        }

        if (($data['status'] ?? null) !== FacilityBooking::STATUS_REJECTED) {
            $data['reject_reason'] = null;
        }

        try {
            // Proxy Pattern: Proxy checks if user is Admin before creating
            $this->bookingProxy->createBooking($data);
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Facility booking created successfully.');
    }

    /**
     * Display the specified resource.
     * Uses Proxy Pattern - proxy controls view access.
     */
    public function show(FacilityBooking $booking): View
    {
        $this->abortIfCannotViewBookingDetails();

        // Proxy Pattern: Verify user can view this booking
        $booking = $this->bookingProxy->getBookingDetails($booking->id);

        return view('admin.bookings.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Proxy will verify admin access when update() is called.
     * Students (including those with president/committee positions) cannot access this.
     */
    public function edit(FacilityBooking $booking): View
    {
        // Proxy Pattern: Block non-admin from accessing edit form
        if (!$this->bookingProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        $facilities = Facility::orderBy('name')->get();

        return view('admin.bookings.edit', [
            'booking' => $booking,
            'facilities' => $facilities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Uses Proxy Pattern - delegates to BookingProxy for access control.
     */
    public function update(BookingUpdateRequest $request, FacilityBooking $booking): RedirectResponse
    {
        $data = $request->validated();

        if (!$this->bookingProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        if (isset($data['status']) && in_array($data['status'], [FacilityBooking::STATUS_APPROVED, FacilityBooking::STATUS_REJECTED], true)) {
            $data['approved_by'] = $request->user()?->id;
        }

        // Clear reject reason when status is not REJECTED
        if (isset($data['status']) && $data['status'] !== FacilityBooking::STATUS_REJECTED) {
            $data['reject_reason'] = null;
        }

        try {
            // Proxy Pattern: Proxy checks if user is Admin before updating
            $this->bookingProxy->updateBooking($booking->id, $data);
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Facility booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Uses Proxy Pattern - delegates to BookingProxy for access control.
     */
    public function destroy(FacilityBooking $booking): RedirectResponse
    {
        if (!$this->bookingProxy->isUserAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Proxy Pattern: Proxy checks if user is Admin before cancelling
        $this->bookingProxy->cancelBooking($booking->id);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Facility booking cancelled.');
    }

    /**
     * Abort when a user is not permitted to view booking details.
     */
    private function abortIfCannotViewBookingDetails(): void
    {
        /** @var \App\Models\User|null $user */
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
}
