<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'image';
    protected $primaryKey = 'imageID';

    protected $fillable = [
        'filePath'
    ];

    /* =======================
     | Relationships
     ======================= */

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'image_post',
            'imageID',
            'postID'
        );
    }
}
