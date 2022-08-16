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

        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'password_confirmation' => Hash::make($request->get('password_confirmation')),
            'type' => $request->get('type'),
        ]);


        $client = new Client();
        $response = $client->post('http://api.cinemas.net/oauth/token',
            [
                'form_params' => [
                    'client_id' => '2',
                    'grant_type' => 'password',
                    'client_secret' => 'MuOmJeE8PSlqNnhWzJsqvYKBFKbB1zwXbIqPbF0F',
                    'username' => $user->email,
                    'password' => $request->get('password'),
                    'scope' => '*',
                ]
            ]
        );
        $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);

        return response($jsonFormattedResult, 200);
    }

}
