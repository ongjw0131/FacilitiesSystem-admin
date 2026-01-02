<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'quantity',
        'unit_price',
        'total_amount',
        'status',
        'ordered_at',
        'expired_at',
        'cancel_reason',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Order 属于 Ticket
    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_id');
    }

    // Order 属于 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
