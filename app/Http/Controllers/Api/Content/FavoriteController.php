<?php

namespace App\Http\Controllers\Api\Content;


use App\Http\Controllers\Controller;
use App\Http\Repositories\UserContentRepository;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $userContentRepository;

    public function __construct(UserContentRepository $userContentRepository)
    {
        $this->userContentRepository = $userContentRepository;
    }

    public function index()
    {
         return $this->userContentRepository->favorite();
    }

    public function addFavorite(Request $request)
    {
         return $this->userContentRepository->addFavorite($request);
    }

    public function removeFavorite(Request $request)
    {
        return $this->userContentRepository->removeFavorite($request);
    }


}
