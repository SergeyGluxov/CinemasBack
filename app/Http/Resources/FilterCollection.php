<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'countries' => CountryResource::collection($this['countries']),
            'genres' => GenreResource::collection($this['genres']),
            'years' => GenreResource::collection($this['years']),
        ];
    }
}
