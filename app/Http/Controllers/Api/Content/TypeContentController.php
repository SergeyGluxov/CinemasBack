<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\TypeContentRepository;
use Illuminate\Http\Request;

class TypeContentController extends Controller
{
    protected $typeContentRepository;

    public function __construct(TypeContentRepository $typeContentRepository)
    {
        $this->typeContentRepository = $typeContentRepository;
    }

    public function index()
    {
        return $this->typeContentRepository->all();
    }

    public function show($id)
    {
        return $this->typeContentRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->typeContentRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->typeContentRepository->destroy($id);
    }
}
