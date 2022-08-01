<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Content\ContentController;
use App\Http\Controllers\Api\Content\ContentCreatorController;
use App\Http\Controllers\Api\Content\ContentGenreController;
use App\Http\Controllers\Api\Content\FeedContentController;
use App\Http\Controllers\Api\Content\GenreController;
use App\Http\Controllers\Api\Content\SearchController;
use App\Http\Controllers\Api\Content\TypeContentController;
use App\Http\Controllers\Api\Creators\CreatorsController;
use App\Http\Controllers\Api\Feed\FeedController;
use App\Http\Controllers\Api\Filter\FilterController;
use App\Http\Controllers\Api\Page\PageController;
use App\Http\Controllers\Api\Page\PageFeedController;
use App\Http\Controllers\Api\Release\ReleaseController;
use App\Http\Controllers\Api\SyncCinemas\SyncCinemasController;
use App\Http\Controllers\Api\SyncCinemas\SyncMoreController;
use App\Http\Controllers\Api\SyncCinemas\SyncPremierController;
use App\Http\Controllers\Api\SyncCinemas\SyncTvigleController;
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
    Route::post('/register', RegisterController::class)->name('register.api');
    Route::post('/logout', LogoutController::class)->name('logout.api');
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('/syncIviFilm', [SyncCinemasController::class, 'syncIviFilm']);
    Route::post('/syncMoreFilms', [SyncMoreController::class, 'syncMoreFilms']);
    Route::post('/syncPremierFilms', [SyncPremierController::class, 'syncPremierFilms']);
    Route::post('/syncTvigleFilms', [SyncTvigleController::class, 'syncTvigleFilms']);
    Route::resource('/type', TypeContentController::class);
    Route::resource('/content', ContentController::class);
    Route::resource('/genre', GenreController::class);
    Route::resource('/contentGenre', ContentGenreController::class);
    Route::resource('/contentCreator', ContentCreatorController::class);
    Route::resource('/feedContent', FeedContentController::class);
    Route::post('/deleteContentFromFeed', [FeedContentController::class, 'deleteContentFromFeed']);
    Route::resource('/feed', FeedController::class);

    Route::resource('/page', PageController::class);
    Route::resource('/pageFeed', PageFeedController::class);

    Route::resource('/creator', CreatorsController::class);

    Route::resource('/release', ReleaseController::class);
    Route::post('/contenteRelease', [ReleaseController::class, 'getContentRelease']);

    Route::post('/search', [SearchController::class, 'search']);
    Route::post('/searchByFilter', [SearchController::class, 'searchByFilter']);

    Route::get('/getFilters', [FilterController::class, 'getFilters']);


});
