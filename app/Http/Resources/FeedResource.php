<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' =>$this->title,
            //'contents'=>ContentShortResource::collection($this->contents()->paginate(1))
            'results'=>new ContentCollection($this->contents()->paginate(10))
        ];
    }
}
