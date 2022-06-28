<?php

namespace App\Http\Controllers\Api\Page;


use App\Http\Controllers\Controller;
use App\Http\Repositories\FeedRepository;
use App\Http\Repositories\PageRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index()
    {
        return $this->pageRepository->all();
    }

    public function show($id)
    {
        return $this->pageRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->pageRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->pageRepository->destroy($id);
    }
}
