<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\FeedContentRepository;
use Illuminate\Http\Request;

class FeedContentController extends Controller
{
    protected $contentGenreRepository;

    public function __construct(FeedContentRepository $contentGenreRepository)
    {
        $this->contentGenreRepository = $contentGenreRepository;
    }

    public function index()
    {
        return $this->contentGenreRepository->all();
    }

    public function show($id)
    {}

    public function store(Request $request)
    {
        return $this->contentGenreRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->contentGenreRepository->destroy($id);
    }

    public function deleteContentFromFeed(Request $request)
    {
        return $this->contentGenreRepository->deleteContentFromFeed($request);
    }
}
