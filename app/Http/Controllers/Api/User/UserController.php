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


    public function update(Request $request, $id)
    {
        return $this->userRepository->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->userRepository->destroy($id);
    }

    public function userProfile(Request  $request)
    {
        return $this->userRepository->userProfile($request);
    }

}
