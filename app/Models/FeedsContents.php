<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedsContents extends Model
{
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
