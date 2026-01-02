<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'commentID';

    protected $fillable = [
        'postID',
        'userID',
        'content',
        'isDelete'
    ];

    /* =======================
     | Relationships
     ======================= */

    public function post()
    {
        return $this->belongsTo(Post::class, 'postID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }


}
