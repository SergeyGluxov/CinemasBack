<?php

namespace App\Http\Repositories;

use App\Http\Resources\ContentShortResource;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


    public function searchByFilter(Request $request)
    {
        $photoQuery = Content::query();
        foreach ($request->json()->get('genres') as $genre) {
            $photoQuery->whereHas('genres', function ($query) use ($genre) {
                return $query->where('genres.id', $genre);
            });
        }

        foreach ($request->json()->get('countries') as $country) {
            $photoQuery->where('country', $country);
        }

        foreach ($request->json()->get('years') as $year) {
            $photoQuery->where('year', $year);
        }

        $photos = $photoQuery->get();

        return ContentShortResource::collection($photos);
    }
}

