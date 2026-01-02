<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'file';
    protected $primaryKey = 'fileID';

    protected $fillable = [
        'filePath',
        'originalName',
        'fileSize'
    ];

    /* =======================
     | Relationships
     ======================= */

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'file_post',
            'fileID',
            'postID'
        );
    }
}
