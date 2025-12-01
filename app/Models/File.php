<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
