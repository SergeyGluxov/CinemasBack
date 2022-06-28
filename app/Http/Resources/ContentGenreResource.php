<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentGenreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'content' => new ContentResource($this->content()->firstOrFail()),
            'genre' => new TypeContentResource($this->genre()->firstOrFail()),
        ];
    }
}
