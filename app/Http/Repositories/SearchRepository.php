<?php

namespace App\Http\Repositories;

use App\Http\Resources\ContentCollection;
use App\Http\Resources\ContentPaginationCollection;
use App\Http\Resources\ContentShortCollection;
use App\Http\Resources\ContentShortPaginationResource;
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


        $photoQuery->where(function ($photoQuery) use ($request) {
            $photoQuery
                ->whereHas('genres', function ($photoQuery) use ($request) {
                    $a = 0;
                    foreach ($request->json()->get('genres') as $genre) {
                        if ($a < 1)
                            $photoQuery->where('genres.id', '=', $genre);
                        else
                            $photoQuery->orWhere('genres.id', '=', $genre);
                        $a++;
                    }
                })
                ->where(function ($photoQuery) use ($request) {
                    foreach ($request->json()->get('countries') as $country) {
                        $photoQuery->orWhere('country_id', $country);
                    }
                })
                ->where(function ($photoQuery) use ($request) {
                    foreach ($request->json()->get('years') as $year) {
                        $photoQuery->orWhere('year', $year);
                    }
                });
            return $photoQuery;
        });
       // dd($photoQuery->toSql());
        $photos = $photoQuery->paginate(10);
        return new ContentShortCollection($photos);

        $photoQuery->whereHas('genres', function ($photoQuery) use ($request) {
            $a = 0;
            foreach ($request->json()->get('genres') as $genre) {
                if ($a < 1)
                    $photoQuery->where('genres.id', '=', $genre);
                else
                    $photoQuery->orWhere('genres.id', '=', $genre);
                $a++;
            }
            return $photoQuery;
        });

        foreach ($request->json()->get('countries') as $country) {
            $photoQuery->orWhere('country_id', $country);
        }

        foreach ($request->json()->get('years') as $year) {
            $photoQuery->orWhere('year', $year);
        }

        dd($photoQuery->toSql());
        $photos = $photoQuery->paginate(10);
        return new ContentShortCollection($photos);
        return new ContentPaginationCollection($photos);
    }
}

