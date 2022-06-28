<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public function contents()
    {
        return $this->belongsToMany('App\Models\Content', 'contents_genres', 'content_id', 'genre_id');
    }
}
