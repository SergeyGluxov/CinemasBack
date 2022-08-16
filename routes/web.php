<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\Social\GithubController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/content', [App\Http\Controllers\HomeController::class, 'content'])->name('content');
Route::get('/admin/contents', [AdminController::class, 'getContents']);
Route::get('/admin/contents/{id}', [AdminController::class, 'getContent']);
Route::get('/admin/feeds', [AdminController::class, 'getFeeds']);
Route::get('/admin/feed/{id}', [AdminController::class, 'getFeed']);
Route::get('/admin/users', [AdminController::class, 'getUsers']);
Route::get('/admin/oauth_clients', function () {
    return view('admin/oauth/oauth_clients');
});

Route::get('/redirect', [AuthController::class, 'redirect']);
Route::get('/callback', [AuthController::class, 'callback']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/login/github', [GithubController::class, 'redirectToProvider']);
Route::get('/login/github/callback', [GithubController::class, 'handleProviderCallback']);

Route::get('/activate/', function () {
    return Redirect::away('cinemas://');
});

