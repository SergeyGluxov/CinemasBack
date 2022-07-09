<?php

namespace App\Http\Repositories;

use App\Http\Resources\ContentShortResource;
use App\Models\Content;
use Illuminate\Http\Request;

class SearchRepository
{
    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function search(Request $request)
    {
        $content = Content::where('title', 'LIKE', '%' . $request->get('query') . '%')->get();
        ContentShortResource::withoutWrapping();
        return ContentShortResource::collection($content);
    }
}

