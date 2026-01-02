<?php

namespace App\Services;

use App\Models\Society;

class SocietyService
{
    /**
     * Get all societies with their members
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSocieties()
    {
        return Society::with('members')->get();
    }

    /**
     * Get a single society by ID
     *
     * @param int $societyID
     * @return Society|null
     */
    public function getSocietyById($societyID)
    {
        return Society::with('members')->find($societyID);
    }

    /**
     * Create a new society
     *
     * @param array $data
     * @return Society
     */
    public function createSociety(array $data)
    {
        return Society::create($data);
    }

    /**
     * Update a society
     *
     * @param int $societyID
     * @param array $data
     * @return Society
     */
    public function updateSociety($societyID, array $data)
    {
        $society = Society::find($societyID);
        if ($society) {
            $society->update($data);
        }
        return $society;
    }

    /**
     * Delete a society (soft delete)
     *
     * @param int $societyID
     * @return bool
     */
    public function deleteSociety($societyID)
    {
        $society = Society::find($societyID);
        if ($society) {
            return $society->update(['isDelete' => 1]);
        }
        return false;
    }

    /**
     * Get societies by status
     *
     * @param bool $isDeleted
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSocietiesByStatus($isDeleted = false)
    {
        return Society::with('members')
            ->where('isDelete', $isDeleted)
            ->get();
    }

    /**
     * Count total societies
     *
     * @return int
     */
    public function getTotalCount()
    {
        return Society::count();
    }
}
