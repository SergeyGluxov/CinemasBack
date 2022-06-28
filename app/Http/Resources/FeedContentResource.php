<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'feed' => new FeedResource($this->feed()->firstOrFail()),
            'content' => new ContentResource($this->content()->firstOrFail()),
        ];
    }
}
