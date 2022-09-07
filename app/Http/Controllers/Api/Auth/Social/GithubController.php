<?php

namespace App\Http\Controllers\Api\Auth\Social;

use App\Http\Controllers\Controller;
use App\Http\Repositories\RegisterRepository;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{

    protected $registerRepository;

    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Просто callback
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    /*public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'github']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);
        $jsonFormattedResult = json_decode($response->getContent(), true);
        return redirect()->away('cinemas://?refresh_token=' . $jsonFormattedResult['refresh_token']);
    }*/

    /**
     * Принимает в аргумент code, по которому можно запросить token, по которому можно запросить пользователя
     **/
    public function handleProviderCallback(Request $request)
    {
        $googleAuthCode = $request->get('code');
        $accessTokenResponse = Socialite::driver('github')->getAccessTokenResponse($googleAuthCode);
        $accessToken = $accessTokenResponse["access_token"];
        //$expiresIn=$accessTokenResponse["expires_in"];
        //$idToken=$accessTokenResponse["id_token"];
        //$refreshToken=isset($accessTokenResponse["refresh_token"])?$accessTokenResponse["refresh_token"]:"";
        //$tokenType=$accessTokenResponse["token_type"];
        $user = Socialite::driver('github')->userFromToken($accessToken);
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'github']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);
        $jsonFormattedResult = json_decode($response->getContent(), true);
        return redirect()->away('cinemas://?refresh_token=' . $jsonFormattedResult['refresh_token']);
    }


    public function authByAccessToken(Request $request)
    {
        $accessToken = $request->get('access_token');
        $user = Socialite::driver('github')->userFromToken($accessToken);
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'github']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);
        $jsonFormattedResult = json_decode($response->getContent(), true);
        return redirect()->away('cinemas://?refresh_token=' . $jsonFormattedResult['refresh_token']);
    }
}
