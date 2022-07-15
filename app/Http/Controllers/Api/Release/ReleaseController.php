<?php

namespace App\Http\Controllers\Api\Release;


use App\Http\Controllers\Controller;
use App\Http\Repositories\ReleaseRepository;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    protected $releaseRepository;

    public function __construct(ReleaseRepository $releaseRepository)
    {
        $this->releaseRepository = $releaseRepository;
    }

    public function index()
    {
        return $this->releaseRepository->all();
    }

    public function show($id)
    {
        return $this->releaseRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->releaseRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->releaseRepository->destroy($id);
    }


    public function getContentRelease(Request $request)
    {
        return $this->releaseRepository->getContentRelease($request);
    }

}
