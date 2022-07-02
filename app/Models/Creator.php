<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    public function contents()
    {
        return $this->belongsToMany('App\Models\Content', 'contents_creators', 'content_id', 'creator_id');
    }
}
