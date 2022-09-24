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

    public function users()
    {
        return $this->belongsToMany(User::class, UsersContents::class, 'content_id', 'user_id');
    }


    public function isFavorite()
    {
        $user = auth()->user()->id;
        return !empty($this->users()->where('user_id', $user)->first());
    }


    public function releases()
    {
        return $this->hasMany(Release::class);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
