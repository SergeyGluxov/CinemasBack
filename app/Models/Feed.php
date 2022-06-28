<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    public function contents()
    {
        return $this->belongsToMany('App\Models\Content', 'feeds_contents',  'feed_id','content_id');
    }
}
