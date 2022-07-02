<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentCreatorRepository;
use Illuminate\Http\Request;

class ContentCreatorController extends Controller
{
    protected $contentCreatorRepository;

    public function __construct(ContentCreatorRepository $contentCreatorRepository)
    {
        $this->contentCreatorRepository = $contentCreatorRepository;
    }

    public function index()
    {
        return $this->contentCreatorRepository->all();
    }

    public function show($id)
    {}

    public function store(Request $request)
    {
        return $this->contentCreatorRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->contentCreatorRepository->destroy($id);
    }
}
