<?php

namespace App\Http\Controllers\Api\Auth\Social;

use App\Http\Controllers\Controller;
use App\Http\Repositories\RegisterRepository;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class VkController extends Controller
{

    protected $registerRepository;

    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
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
        $authRequest->request->add(['client_secret' => 'L3OFt6qSTGsp79pP6AUYW0ZIbPIpLofU0tGw4SIU']);
        $authRequest->request->add(['client_id' => 4]);
        return $this->registerRepository->register($authRequest);


       /* $userStorage = User::where('email', "gluxov-103@gmail.com")->first();
        if (!$userStorage) {
            User::create([
                'name' => $user->getName(),
                'email' => 'gluxov-103@gmail.com',
                'password' => Hash::make('123123123'),
                'password_confirmation' => Hash::make('123123123'),
                'type' => 0,
            ]);
            $client = new Client();
            $response = $client->post('http://api.cinemas.net/oauth/token', [
                    'form_p/callback?code=c3ee1d26e18cac3773b4&state=MozBXnxzv33M3B6EYH74h95fCUTL1cKQIRNng0Ibarams' => [
                        'client_id' => '5',
                        'grant_type' => 'password',
                        'client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8',
                        'username' => 'gluxov-103@gmail.com',
                        'password' => '123123123',
                        'scope' => '*',
                    ]
                ]
            );
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            return redirect()->away('cinemas://?refresh_token='.$jsonFormattedResult['refresh_token']);

            return redirect()->away('intent://#Intent;action=ru.cinemas.glukhov;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end');
            return response($jsonFormattedResult, 200);
        } else {
            $client = new Client();
            $response = $client->post('http://api.cinemas.net/oauth/token', [
                    'form_params' => [
                        'client_id' => '5',
                        'grant_type' => 'password',
                        'client_secret' => '9On3hGG541wNSjw7uZXt4DIjvUc60qJUejm0Ycq8',
                        'username' => 'gluxov-103@gmail.com',
                        'password' => '123123123',
                        'scope' => '*',
                    ]
                ]
            );
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            return redirect()->away('cinemas://?refresh_token='.$jsonFormattedResult['refresh_token']);
            return redirect()->away('intent://#Intent;action=ru.cinemas.glukhov;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end');
            //return redirect('ru.cinemas.glukhov://intent:#Intent;action=ru.cinemas.glukhov;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end');
            return response($jsonFormattedResult, 200);
        }*/
    }
}
