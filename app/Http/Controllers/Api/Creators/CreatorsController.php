<?php

namespace App\Http\Controllers\Api\Creators;


use App\Http\Controllers\Controller;
use App\Http\Repositories\CreatorRepository;
use Illuminate\Http\Request;

class CreatorsController extends Controller
{
    protected $creatorsRepository;

    public function __construct(CreatorRepository $creatorsRepository)
    {
        $this->creatorsRepository = $creatorsRepository;
    }

    public function index()
    {
        return $this->creatorsRepository->all();
    }

    public function show($id)
    {
        return $this->creatorsRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->creatorsRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->creatorsRepository->destroy($id);
    }
}
