<?php

namespace App\Http\Controllers\Api\Auth\Social;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ProfileRepository;
use App\Http\Repositories\RegisterRepository;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class VkController extends Controller
{

    protected $registerRepository;
    protected $profileRepository;

    public function __construct(RegisterRepository $registerRepository, ProfileRepository $profileRepository)
    {
        $this->registerRepository = $registerRepository;
        $this->profileRepository = $profileRepository;
    }

    public function redirectToProvider()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('vkontakte')->user();
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'vk']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);
        $jsonFormattedResult = json_decode($response->getContent(), true);
        dd($user);
        return redirect()->away('cinemas://?refresh_token=' . $jsonFormattedResult['refresh_token']);
    }

    public function authByAccessToken(Request $request)
    {
        $accessToken = $request->get('access_token');
        $user = Socialite::driver('vkontakte')->userFromToken($accessToken);
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'vkontakte']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);

        if ($response->getStatusCode() == 200) {
            $user =
            $profileRequest = Request::create('POST');
            $profileRequest->request->add(['user_id' => auth()->guard('api')->user()->id]);
            $profileRequest->request->add(['email' => $user->getEmail()]);
            $profileRequest->request->add(['provider' => 'vkontakte']);
            $responseCreateProfile = $this->profileRepository->createOrSelectProfile($authRequest);
            dd($responseCreateProfile);
        }

        $jsonFormattedResult = json_decode($response->getContent(), true);
        return $jsonFormattedResult;
    }
}
