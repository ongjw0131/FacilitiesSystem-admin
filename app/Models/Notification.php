<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'notificationID';
    public $timestamps = true;

    protected $fillable = [
        'userID',
        'societyID',
        'postID',
        'type',
        'title',
        'message',
    ];

    protected $casts = [];

    /* =======================
     | Relationships
     ======================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function society()
    {
        return $this->belongsTo(Society::class, 'societyID');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }
}
