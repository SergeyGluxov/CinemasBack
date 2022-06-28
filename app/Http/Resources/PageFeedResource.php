<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageFeedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'feed' => new FeedResource($this->feed()->firstOrFail()),
            'page' => new PageResource($this->page()->firstOrFail()),
        ];
    }
}
