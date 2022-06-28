<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagesFeeds extends Model
{
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
