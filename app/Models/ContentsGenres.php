<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentsGenres extends Model
{
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
