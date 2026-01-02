<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'postID';

    protected $fillable = [
        'userID',
        'societyID',
        'title',
        'content',
        'isDelete'
    ];

    /* =======================
     | Relationships
     ======================= */

    public function society()
    {
        return $this->belongsTo(Society::class, 'societyID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'postID');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'postID');
    }

    public function images()
    {
        return $this->belongsToMany(
            Image::class,
            'image_post',
            'postID',
            'imageID'
        );
    }

    public function files()
    {
        return $this->belongsToMany(
            File::class,
            'file_post',
            'postID',
            'fileID'
        );
    }
}
