<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\SearchRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function search(Request $request)
    {
        return $this->searchRepository->search($request);
    }
}
