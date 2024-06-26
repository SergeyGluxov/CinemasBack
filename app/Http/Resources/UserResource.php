<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' =>$this->name,
            'email' =>$this->email,
            'provider' =>$this->provider,
            'roles' => RolesResource::collection($this->roles),
            'profile' => $this->profiles()->where('is_selected', 1)->first()
        ];
    }
}
