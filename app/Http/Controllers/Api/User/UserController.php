<?php

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->userRepository->all();
    }

    public function show($id)
    {
        return $this->userRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->userRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->userRepository->destroy($id);
    }

    public function profile(Request  $request)
    {
        return $this->userRepository->profile($request);
    }

}
