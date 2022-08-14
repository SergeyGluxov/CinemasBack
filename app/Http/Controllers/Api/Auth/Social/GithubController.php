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

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();
        $storeCreator = Request::create('POST');
        $storeCreator->request->add(['name' => $user->getName()]);
        /*$user->user['login']*/
        $storeCreator->request->add(['email' => 'gluxov-102@gmail.com']);
        $storeCreator->request->add(['password' => '123132123']);
        $storeCreator->request->add(['password_confirmation' => '123132123']);
        $storeCreator->request->add(['type' => 0]);

        return $this->registerRepository->register($storeCreator);

    }
}
