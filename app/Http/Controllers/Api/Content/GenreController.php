<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\GenreRepository;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    protected $genreRepository;

    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    public function index()
    {
        return $this->genreRepository->all();
    }

    public function show($id)
    {
        return $this->genreRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->genreRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->genreRepository->destroy($id);
    }
}
