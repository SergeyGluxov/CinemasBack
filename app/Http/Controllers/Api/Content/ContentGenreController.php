<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentGenreRepository;
use Illuminate\Http\Request;

class ContentGenreController extends Controller
{
    protected $contentGenreRepository;

    public function __construct(ContentGenreRepository $contentGenreRepository)
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
}
