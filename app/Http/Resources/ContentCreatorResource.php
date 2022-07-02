<?php

namespace App\Http\Resources;

use App\Models\Creator;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentCreatorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'content' => new ContentResource($this->content()->firstOrFail()),
            'creator' => new Creator($this->creator()->firstOrFail()),
        ];
    }
}
