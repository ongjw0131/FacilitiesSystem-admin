<?php

namespace App\Services\Facility;

use App\Models\Facility;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * FacilityService - Real Subject in Proxy Pattern
 * 
 * This class contains the actual business logic for facility operations.
 * It does NOT perform any role or permission checking.
 * Access control is delegated to the FacilityProxy.
 */
class FacilityService
{
    /**
     * Retrieve a paginated list of facilities grouped by name, type, and location.
     * 
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getFacilityList(int $perPage = 10): LengthAwarePaginator
    {
        return Facility::select(
            'name',
            'type',
            'location',
            DB::raw('MIN(id) as representative_id'),
            DB::raw('MIN(capacity) as capacity'),
            DB::raw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count')
        )
            ->groupBy('name', 'type', 'location')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Create a new facility with multiple venues.
     * 
     * @param array $data Facility data including venue_prefix and number_of_venues
     * @return void
     * @throws \RuntimeException If venue ID already exists
     */
    public function createFacility(array $data): void
    {
        $data['is_active'] = $data['is_active'] ?? true;

        $prefix = strtoupper(trim($data['venue_prefix']));
        $count = (int) $data['number_of_venues'];
        unset($data['venue_prefix'], $data['number_of_venues']);

        $baseNumber = 101;

        DB::transaction(function () use ($data, $prefix, $count, $baseNumber) {
            for ($i = 0; $i < $count; $i++) {
                $venueId = $prefix . ($baseNumber + $i);

                if (Facility::withTrashed()->where('venue_id', $venueId)->exists()) {
                    throw new \RuntimeException("Venue ID {$venueId} already exists. Please adjust prefix or number of venues.");
                }

                Facility::create(array_merge($data, [
                    'venue_id' => $venueId,
                ]));
            }
        });
    }

    /**
     * Update an existing facility.
     * 
     * @param int $facilityId Facility ID to update
     * @param array $data Updated facility data
     * @return Facility Updated facility instance
     */
    public function updateFacility(int $facilityId, array $data): Facility
    {
        return DB::transaction(function () use ($facilityId, $data) {
            $facility = Facility::findOrFail($facilityId);

            $targetVenueCount = null;
            if (array_key_exists('number_of_venues', $data)) {
                $targetVenueCount = (int) $data['number_of_venues'];
                unset($data['number_of_venues']);
            }

            unset($data['venue_id']);

            $groupQuery = Facility::where('name', $facility->name)
                ->where('type', $facility->type)
                ->where('location', $facility->location);

            if (!empty($data)) {
                $groupQuery->update($data);
            }

            $facility->refresh();

            if ($targetVenueCount === null) {
                return $facility;
            }

            $groupFacilities = Facility::where('name', $facility->name)
                ->where('type', $facility->type)
                ->where('location', $facility->location)
                ->orderBy('id')
                ->get();

            $currentCount = $groupFacilities->count();

            if ($targetVenueCount > $currentCount) {
                [$prefix, $maxNumber] = $this->resolveVenuePrefixAndMaxNumber($groupFacilities);
                $toCreate = $targetVenueCount - $currentCount;

                for ($i = 0; $i < $toCreate; $i++) {
                    $venueNumber = $maxNumber + 1 + $i;
                    $venueId = $prefix . $venueNumber;

                    if (Facility::withTrashed()->where('venue_id', $venueId)->exists()) {
                        throw new \RuntimeException("Venue ID {$venueId} already exists. Please adjust the number of venues.");
                    }

                    Facility::create([
                        'name' => $facility->name,
                        'venue_id' => $venueId,
                        'type' => $facility->type,
                        'location' => $facility->location,
                        'capacity' => $facility->capacity,
                        'description' => $facility->description,
                        'facility_image_path' => $facility->facility_image_path,
                        'is_active' => $facility->is_active,
                    ]);
                }
            } elseif ($targetVenueCount < $currentCount) {
                $toDelete = $currentCount - $targetVenueCount;

                $sorted = $groupFacilities->sortBy(function (Facility $facility) {
                    return $this->extractVenueNumber($facility->venue_id) ?? PHP_INT_MAX;
                })->values();

                $sorted->reverse()->take($toDelete)->each(function (Facility $facility) {
                    $facility->delete();
                });
            }

            return $facility;
        });
    }

    /**
     * @param \Illuminate\Support\Collection<int, Facility> $facilities
     * @return array{string, int}
     */
    private function resolveVenuePrefixAndMaxNumber($facilities): array
    {
        $prefix = '';
        $maxNumber = null;

        foreach ($facilities as $facility) {
            [$currentPrefix, $currentNumber] = $this->parseVenueId($facility->venue_id);
            if ($currentNumber === null) {
                continue;
            }
            if ($prefix === '') {
                $prefix = $currentPrefix;
            }
            $maxNumber = $maxNumber === null ? $currentNumber : max($maxNumber, $currentNumber);
        }

        if ($maxNumber === null) {
            $prefix = $facilities->first()?->venue_id ?? '';
            $maxNumber = 100;
        }

        return [$prefix, $maxNumber];
    }

    /**
     * @return array{string, int|null}
     */
    private function parseVenueId(string $venueId): array
    {
        if (preg_match('/^(.*?)(\d+)$/', $venueId, $matches)) {
            return [$matches[1], (int) $matches[2]];
        }

        return [$venueId, null];
    }

    private function extractVenueNumber(string $venueId): ?int
    {
        [, $number] = $this->parseVenueId($venueId);

        return $number;
    }

    /**
     * Deactivate a facility (soft deletion).
     * 
     * @param int $facilityId Facility ID to deactivate
     * @return Facility Deactivated facility instance
     */
    public function deactivateFacility(int $facilityId): Facility
    {
        $facility = Facility::findOrFail($facilityId);
        $facility->update(['is_active' => false]);
        
        return $facility;
    }

    /**
     * Get a single facility by ID.
     * 
     * @param int $facilityId Facility ID
     * @return Facility
     */
    public function getFacilityById(int $facilityId): Facility
    {
        return Facility::findOrFail($facilityId);
    }
}
