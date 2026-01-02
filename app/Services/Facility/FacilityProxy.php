<?php

namespace App\Services\Facility;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * FacilityProxy - Proxy Pattern Implementation
 * 
 * Acts as a gatekeeper between the controller and FacilityService.
 * Permissions:
 * - admin: Full access (list, details, create, update, deactivate)
 * - student with society position (president/committee): List + details
 * - student without position: List only
 * 
 * This centralizes permission logic instead of scattering it across controllers.
 */
class FacilityProxy
{
    /**
     * The real subject (FacilityService) that performs actual operations.
     */
    private FacilityService $facilityService;

    /**
     * Constructor - Dependency Injection of FacilityService
     * 
     * @param FacilityService $facilityService The real service to delegate to
     */
    public function __construct(FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
    }

    /**
     * Get facility list - Accessible by Admin and Student (view-only).
     */
    public function getFacilityList(int $perPage = 10): LengthAwarePaginator
    {
        if ($this->canViewFacilityList()) {
            return $this->facilityService->getFacilityList($perPage);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Create a new facility - Admin only
     * 
     * @param array $data Facility data
     * @return void
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function createFacility(array $data): void
    {
        // Only Admin can create facilities
        if ($this->isAdmin()) {
            $this->facilityService->createFacility($data);
            return;
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Update an existing facility - Admin only
     * 
     * @param int $facilityId Facility ID
     * @param array $data Updated data
     * @return Facility
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function updateFacility(int $facilityId, array $data): Facility
    {
        // Only Admin can update facilities
        if ($this->isAdmin()) {
            return $this->facilityService->updateFacility($facilityId, $data);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Deactivate a facility - Admin only
     * 
     * @param int $facilityId Facility ID
     * @return Facility
     * @throws AccessDeniedHttpException If user is not an admin
     */
    public function deactivateFacility(int $facilityId): Facility
    {
        // Only Admin can deactivate facilities
        if ($this->isAdmin()) {
            return $this->facilityService->deactivateFacility($facilityId);
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Get a single facility by ID - Accessible by Admin and student leaders.
     */
    public function getFacilityById(int $facilityId): Facility
    {
        // Allow access for Admin, President, and Committee
        if ($this->canViewFacilityDetails()) {
            return $this->facilityService->getFacilityById($facilityId);
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
     * Check if the current user can view facilities list.
     */
    private function canViewFacilityList(): bool
    {
        return $this->isAdmin() || $this->isStudent();
    }

    /**
     * Check if the current user can view facility details.
     */
    private function canViewFacilityDetails(): bool
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
