<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;

class FacilityVenueController extends Controller
{
    /**
     * Return active venues for the given facility group (by name).
     */
    public function index(int $facility): JsonResponse
    {
        $facilityModel = Facility::withTrashed()->find($facility);

        if (!$facilityModel) {
            return response()->json([
                'status' => 'F',
                'message' => 'Facility not found.',
            ], 404);
        }

        $venues = Facility::where('name', $facilityModel->name)
            ->where('is_active', true)
            ->orderBy('venue_id')
            ->get(['id', 'venue_id', 'name']);

        return response()->json([
            'status' => 'S',
            'venues' => $venues,
        ]);
    }
}
