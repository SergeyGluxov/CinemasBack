<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentShortResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' =>$this->title,
            'rating' =>$this->rating,
            'restrict' =>$this->restrict,
            'year' =>$this->year,
            'poster' =>$this->poster,
        ];
    }
}
