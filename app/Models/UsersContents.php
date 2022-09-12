<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersContents extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
