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
                'client_id' => '3',
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
                'client_id' => '3',
                'client_secret' => 'PmwV9XwkzCEqyUeyqiUZfLh5y3dtxGGcajh44rwD',
                'username' => 'gluxov2@gmail.com',
                'password' => '123123123',
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
