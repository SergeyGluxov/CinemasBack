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
            'description' =>$this->description,
            'rating' =>$this->rating,
            'restrict' =>$this->restrict,
            'year' =>$this->year,
            'country' =>$this->country,
            'duration' =>$this->duration,
            'type_content' => new TypeContentResource($this->typeContent('title')->firstOrFail()),
            'genres' => GenreResource::collection($this->genres)
        ];
    }
}
