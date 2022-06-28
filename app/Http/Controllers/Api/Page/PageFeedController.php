<?php

namespace App\Http\Controllers\Api\Page;


use App\Http\Controllers\Controller;
use App\Http\Repositories\PageFeedRepository;
use Illuminate\Http\Request;

class PageFeedController extends Controller
{
    protected $pageFeedRepository;

    public function __construct(PageFeedRepository $pageFeedRepository)
    {
        $this->pageFeedRepository = $pageFeedRepository;
    }

    public function index()
    {
        return $this->pageFeedRepository->all();
    }

    public function show($id)
    {
        return $this->pageFeedRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->pageFeedRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->pageFeedRepository->destroy($id);
    }
}
