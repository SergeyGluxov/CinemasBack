<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReleaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cinema' =>$this->cinema,
            'type' =>$this->type,
            'url' =>$this->url
        ];
    }
}
