<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'countries' => $this->countries,
            'genres' => $this->genres
        ];
    }
}
