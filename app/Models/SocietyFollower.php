<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyFollower extends Model
{
    protected $table = 'society_follower';
    protected $primaryKey = 'followerID';

    protected $fillable = [
        'userID',
        'societyID',
    ];

    protected $casts = [
        'followedDate' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    /**
     * Get the society
     */
    public function society()
    {
        return $this->belongsTo(Society::class, 'societyID', 'societyID');
    }
}
