<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'venue_id',
        'type',
        'location',
        'capacity',
        'description',
        'facility_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get all bookings for this facility.
     */
    public function facilityBookings(): HasMany
    {
        return $this->hasMany(FacilityBooking::class);
    }
}
