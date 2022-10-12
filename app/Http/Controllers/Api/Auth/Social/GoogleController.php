<?php

namespace App\Http\Controllers\Api\Auth\Social;

use App\Http\Controllers\Controller;
use App\Http\Repositories\RegisterRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserRoleRepository;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class GoogleController extends Controller
{

    protected $registerRepository;
    protected $userRoleRepository;
    protected $userRepository;

    public function __construct(RegisterRepository $registerRepository,
                                UserRoleRepository $userRoleRepository,
                                UserRepository $userRepository)
    {
        $this->registerRepository = $registerRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->userRepository = $userRepository;
    }

    public function authByAccessToken(Request $request)
    {
        $accessToken = $request->get('access_token');
        $driver = Socialite::driver('google');
        $access_token = $driver->getAccessTokenResponse($accessToken)['access_token'];
        $user = $driver->userFromToken($access_token);
        $authRequest = Request::create('POST');
        $authRequest->request->add(['name' => $user->getName()]);
        $authRequest->request->add(['email' => $user->getEmail()]);
        $authRequest->request->add(['nickname' => $user->getNickname()]);
        $authRequest->request->add(['provider' => 'google']);
        $authRequest->request->add(['grant_type' => 'password']);
        $authRequest->request->add(['client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8']);
        $authRequest->request->add(['client_id' => 5]);
        $response = $this->registerRepository->register($authRequest);
        $jsonFormattedResult = json_decode($response->getContent(), true);


        $client = new Client();
        $response = $client->get('http://localhost:8001/api/profile',
            ['headers' => ['Authorization' => 'Bearer ' . $jsonFormattedResult['access_token']]]
        );

        $roleRequest = Request::create('POST');
        $roleRequest->request->add(['user_id' => json_decode($response->getBody()->getContents(), true)['id']]);
        $roleRequest->request->add(['role_id' => 2]); //2 role - auth user
        $this->userRoleRepository->store($roleRequest);
        return $jsonFormattedResult;
    }
}
