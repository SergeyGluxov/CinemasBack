<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    public function releases()
    {
        return $this->hasMany(Release::class);
    }

}
