<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Пользователи, которые принадлежат данной роли.
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'users_roles', 'user_id', 'role_id');
    }
}
