<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\FeedContentRepository;
use App\Http\Repositories\UserContentRepository;
use Illuminate\Http\Request;

class UserContentController extends Controller
{
    protected $userContentRepository;

    public function __construct(UserContentRepository $userContentRepository)
    {
        $this->userContentRepository = $userContentRepository;
    }

    public function index()
    {
        return $this->userContentRepository->all();
    }

    public function show($id)
    {}

    public function store(Request $request)
    {
        return $this->userContentRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->userContentRepository->destroy($id);
    }
}
