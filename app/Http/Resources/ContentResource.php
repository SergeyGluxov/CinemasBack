<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
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
            'country' =>new CountryResource($this->country),
            'kinopoisk_id' =>$this->kinopoisk_id,
            'duration' =>$this->duration,
            'poster' =>$this->poster,
            'type_content' => new TypeContentResource($this->typeContent('title')->firstOrFail()),
            'genres' => GenreResource::collection($this->genres),
            'creators' => CreatorResource::collection($this->creators)
        ];
    }
}
