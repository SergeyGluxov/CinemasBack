<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentRepository;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function index()
    {
        return $this->contentRepository->all();
    }

    public function show($id)
    {
        return $this->contentRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->contentRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->contentRepository->destroy($id);
    }

    public function syncIviFilm()
    {
        return $this->contentRepository->syncIviFilm();
    }

    public function syncMoreFilms()
    {
        return $this->contentRepository->syncMoreFilms();
    }


}
