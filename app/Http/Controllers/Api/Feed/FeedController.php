<?php

namespace App\Http\Controllers\Api\Feed;


use App\Http\Controllers\Controller;
use App\Http\Repositories\FeedRepository;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function index()
    {
        return $this->feedRepository->all();
    }

    public function show($id)
    {
        return $this->feedRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->feedRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->feedRepository->destroy($id);
    }
}
