<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ContentPaginationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        ContentShortResource::withoutWrapping();

        return [
            'contents' => ContentShortResource::collection($this->collection),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ],
        ];
    }



}
