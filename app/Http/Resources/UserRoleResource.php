<?php

namespace App\Http\Resources;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => new UserResource($this->user()->firstOrFail()),
            'role' => new RolesResource($this->role()->firstOrFail()),
        ];
    }
}
