<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Content\ContentController;
use App\Http\Controllers\Api\Content\ContentGenreController;
use App\Http\Controllers\Api\Content\FeedContentController;
use App\Http\Controllers\Api\Content\GenreController;
use App\Http\Controllers\Api\Content\TypeContentController;
use App\Http\Controllers\Api\Creators\CreatorsController;
use App\Http\Controllers\Api\Feed\FeedController;
use App\Http\Controllers\Api\Page\PageController;
use App\Http\Controllers\Api\Page\PageFeedController;
use App\Http\Controllers\Api\SyncCinemas\SyncCinemasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::post('/login', LoginController::class)->name('login.api');
    Route::post('/register',RegisterController::class)->name('register.api');
    Route::post('/logout',LogoutController::class)->name('logout.api');
});


Route::group(['middleware'=>'auth:api'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('/syncIviFilm', [SyncCinemasController::class,'syncIviFilm']);
    Route::post('/syncMoreFilms', [SyncCinemasController::class,'syncMoreFilms']);
    Route::resource('/type', TypeContentController::class);
    Route::resource('/content', ContentController::class);
    Route::resource('/genre', GenreController::class);
    Route::resource('/contentGenre', ContentGenreController::class);
    Route::resource('/feedContent', FeedContentController::class);
    Route::resource('/feed', FeedController::class);

    Route::resource('/page', PageController::class);
    Route::resource('/pageFeed', PageFeedController::class);

    Route::resource('/creator', CreatorsController::class);


});
