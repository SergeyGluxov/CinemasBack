<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public function typeContent()
    {
        return $this->belongsTo(TypeContent::class);
    }

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre', 'contents_genres', 'content_id', 'genre_id');
    }

    public function creators()
    {
        return $this->belongsToMany('App\Models\Creator', 'contents_creators', 'content_id', 'creator_id');
    }


    public function releases()
    {
        return $this->hasMany(Release::class);
    }
}
