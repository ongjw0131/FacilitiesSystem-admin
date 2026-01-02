<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSociety extends Model
{
    protected $table = 'event_society'; 

    protected $fillable = [
        'event_id',
        'society_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
}
