<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => new UserResource($this->user()->firstOrFail()),
            'content' => new ContentResource($this->content()->firstOrFail()),
        ];
    }
}
