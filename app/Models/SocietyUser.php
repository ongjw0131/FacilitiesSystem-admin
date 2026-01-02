<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SocietyUser extends Model
{
    protected $table = 'society_user';
    protected $primaryKey = 'societyUserID';

    protected $fillable = [
        'userID',
        'societyID',
        'position',
        'status',
        'joinedDate',
        'leftDate',
        'appointedBy',
        'kickedBy',
        'kickedDate'
    ];

    /* =======================
     | Relationships
     ======================= */

    public function society()
    {
        return $this->belongsTo(Society::class, 'societyID');
    }

    // Reference to User model (not included here)
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    // User who appointed/promoted
    public function appointedByUser()
    {
        return $this->belongsTo(User::class, 'appointedBy');
    }

    // User who kicked
    public function kickedByUser()
    {
        return $this->belongsTo(User::class, 'kickedBy');
    }
    /* =======================
     | Scopes
     ======================= */

    public function scopeActivePresident(Builder $query): Builder
    {
        return $query
            ->where('position', 'president')
            ->where('status', 'active');
    }

}
