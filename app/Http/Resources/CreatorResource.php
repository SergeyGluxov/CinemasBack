<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kinopoisk_id' => $this->kinopoisk_id,
            'name' => $this->name,
            'eng_name' => $this->eng_name,
            'avatar' => $this->avatar
        ];
    }
}
