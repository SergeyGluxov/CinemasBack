<?php

namespace App\Http\Controllers\Api\SyncCinemas;


use App\Http\Controllers\Controller;
use App\Http\Repositories\SyncCinemasRepository;
use Illuminate\Http\Request;

class SyncCinemasController extends Controller
{
    protected $syncCiemasRepository;

    public function __construct(SyncCinemasRepository $syncCiemasRepository)
    {
        $this->syncCiemasRepository = $syncCiemasRepository;
    }

    public function syncIviFilm()
    {
        return $this->syncCiemasRepository->syncIviFilm();
    }

    public function syncMoreFilms()
    {
        return $this->syncCiemasRepository->syncMoreFilms();
    }


}
