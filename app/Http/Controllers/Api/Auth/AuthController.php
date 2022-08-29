<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => '5',
            'redirect_uri' => 'http://localhost:8000',
            'response_type' => 'token',
            'scope' => '',
        ]);

        return redirect('/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $client = new Client();
        $response = $client->post('/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '5',
                'client_secret' => 'MuOmJeE8PSlqNnhWzJsqvYKBFKbB1zwXbIqPbF0F',
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);
        $response = json_decode($response->getBody());
        $request->user()->token()->delete();
        $request->user()->token()->create([
            'access_token' => $response->access_token,
        ]);

        /*      session()->put('token', json_decode((string) $response->getBody(), true));*/
        return redirect('/home');
    }


}
