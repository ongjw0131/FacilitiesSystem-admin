<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $table = 'society';
    protected $primaryKey = 'societyID';
    public $timestamps = true;

    protected $fillable = [
        'societyName',
        'societyDescription',
        'societyPhotoPath',
        'joinType',
        'whoCanPost',
        'isDelete'
    ];

    /* =======================
     | Relationships
     ======================= */

    // Members of the society
    public function members()
    {
        return $this->hasMany(SocietyUser::class, 'societyID');
    }

    // Posts in the society
    public function posts()
    {
        return $this->hasMany(Post::class, 'societyID');
    }

    // Notifications for this society
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'societyID');
    }

    // Followers of the society
    public function followers()
    {
        return $this->hasMany(SocietyFollower::class, 'societyID');
    }

    
    public function presidents()
    {
        return $this->hasMany(SocietyUser::class, 'societyID')
            ->where('position', 'president')
            ->where('status', 'active');
    }
}
