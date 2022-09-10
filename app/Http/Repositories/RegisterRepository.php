<?php

namespace App\Http\Repositories;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterRepository
{
    public function register(Request $request)
    {
        $passwordDefault = '123123123';
        $userStorage = null;

        //Если нет User, создать его
        if (!empty($request->get('device_uid'))) {
            $userStorage = User::where('device_uid', $request->get('device_uid'))->first();
        }

        if (!$userStorage) {
            $userStorage = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'nickname' => $request->get('nickname'),
                'provider' => $request->get('provider'),
                'password' => Hash::make($passwordDefault),
                'password_confirmation' => Hash::make($passwordDefault),
                'type' => 0,
                'device_uid' => $request->get('device_uid')
            ]);
        }

        //todo  Профиль на этом этапе не создается, если нет профиля -> не авторизован
        $client = new Client();
        $response = $client->post('http://localhost:8001/oauth/token',
            [
                'form_params' => [
                    'client_id' => $request->get('client_id'),
                    'grant_type' => $request->get('grant_type'),
                    'client_secret' => $request->get('client_secret'),
                    'username' => $userStorage->id,
                    'password' => '123123123',
                    'scope' => '*',
                ]
            ]
        );
        $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
        return response($jsonFormattedResult, 200);
    }

}
