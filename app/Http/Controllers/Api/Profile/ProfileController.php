<?php

namespace App\Http\Controllers\Api\Profile;


use App\Http\Controllers\Controller;
use App\Http\Repositories\ProfileRepository;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index()
    {
        return $this->profileRepository->all();
    }

    public function show($id)
    {
        return $this->profileRepository->find($id);
    }

    public function store(Request $request)
    {
        return $this->profileRepository->store($request);
    }

    public function destroy($id)
    {
        return $this->profileRepository->destroy($id);
    }


    public function userProfile()
    {
        return $this->profileRepository->userProfile();
    }

    public function createOrSelectProfile(Request $request)
    {
        return $this->profileRepository->createOrSelectProfile($request);
    }
}
