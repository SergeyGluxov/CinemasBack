<?php

namespace App\Http\Repositories;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterRepository
{
    public function register(Request $request)
    {
        $passwordDefault = '123123123';
        $userStorage = null;
        //Если есть nickname (некоторые сервисы не предоставляют email)

        if(!empty($request->get('email'))){
            $userStorage = User::where('email', $request->get('email'))->first();
        } else if(!empty($request->get('nickname'))) {
            $userStorage = User::where('nickname', $request->get('nickname'))
                ->where('provider',$request->get('provider'))
                ->first();
        } else if(!empty($request->get('device_uid'))){
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
                'device_uid'=>$request->get('device_uid')
            ]);
        }

        $client = new Client();
        $response = $client->post('http://vagontv.ru/oauth/token',
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
