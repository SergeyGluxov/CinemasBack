<?php

namespace App\Http\Controllers\Api\Episode;


use App\Http\Controllers\Controller;
use App\Http\Repositories\EpisodeRepository;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected $episodeRepository;

    public function __construct(EpisodeRepository $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
    }

    public function index()
    {
        return $this->episodeRepository->all();
    }

    public function show($id)
    {
        return $this->episodeRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->episodeRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->episodeRepository->destroy($id);
    }


    public function getSeasonEpisode(Request $request)
    {
        return $this->episodeRepository->getSeasonEpisode($request);
    }

}
