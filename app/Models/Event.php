<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    class Event extends Model
    {
        protected $fillable = [
            'name',
            'description',
            'start_date',
            'end_date',
            'location',
            'capacity',
            'organizer_id',
            'status',
            'is_deleted',
            'image_url_path',
        ];

        protected $casts = [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];

        public function organizer(): BelongsTo
        {
            return $this->belongsTo(User::class, 'organizer_id');
        }

        public function facilityBookings(): HasMany
        {
            return $this->hasMany(FacilityBooking::class);
        }

        // added: many-to-many attendees relation (uses event_user pivot table)
        public function attendees(): BelongsToMany
        {
            return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')->withTimestamps();
        }
    
        public function tickets(): HasMany
        {
            return $this->hasMany(EventTicket::class, 'event_id');
        }

        // many-to-many societies relation (uses event_society pivot table)
        public function societies(): BelongsToMany
        {
            return $this->belongsToMany(Society::class, 'event_society', 'event_id', 'society_id')->withTimestamps();
        }
    }
