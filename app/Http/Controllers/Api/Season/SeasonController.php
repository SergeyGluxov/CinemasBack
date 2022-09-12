<?php

namespace App\Http\Controllers\Api\Season;


use App\Http\Controllers\Controller;
use App\Http\Repositories\SeasonRepository;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    protected $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

    public function index()
    {
        return $this->seasonRepository->all();
    }

    public function show($id)
    {
        return $this->seasonRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->seasonRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->seasonRepository->destroy($id);
    }


    public function getContentSeason(Request $request)
    {
        return $this->seasonRepository->getContentSeason($request);
    }

}
