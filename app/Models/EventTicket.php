<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    
    protected $guarded = [];

    protected $fillable = [
        'event_id',
        'ticket_name',
        'price',
        'total_quantity',
        'sold_quantity',
        'sales_start_at',
        'sales_end_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'sales_start_at' => 'datetime',
        'sales_end_at'   => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
    {
        return $this->hasMany(TicketOrder::class, 'ticket_id');
    }
}
