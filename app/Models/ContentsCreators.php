<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentsCreators extends Model
{
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function cretor()
    {
        return $this->belongsTo(Creator::class);
    }
}
