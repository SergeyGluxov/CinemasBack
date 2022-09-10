<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'provider' => $this->provider,
            'user' =>$this->user
        ];
    }
}
