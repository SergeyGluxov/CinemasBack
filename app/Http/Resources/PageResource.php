<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' =>$this->title,
            'feeds'=>FeedResource::collection($this->feeds)
        ];
    }
}
