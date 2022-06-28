<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function feeds()
    {
        return $this->belongsToMany('App\Models\Feed', 'pages_feeds',  'page_id','feed_id');
    }
}
