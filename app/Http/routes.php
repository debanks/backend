<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => ['api']], function() {
    Route::get('api/home', ['uses' => 'ContentController@home']);
    Route::get('api/fortnite', ['uses' => 'FortniteController@home']);
    Route::post('api/fortnite', ['uses' => 'FortniteController@submit']);
    Route::get('api/stats', ['uses' => 'FortniteController@stats']);
    Route::get('api/stats/{name}', ['uses' => 'FortniteController@player']);
    Route::get('api/header', ['uses' => 'ContentController@header']);
    Route::get('api/content', ['uses' => 'ContentController@index']);
    Route::get('api/content/{id}', ['uses' => 'ContentController@getArticle']);
    Route::get('api/programming', ['uses' => 'ContentController@programming']);
    Route::get('api/programming/{name}', ['uses' => 'ContentController@singleProject']);
    Route::get('api/games', ['uses' => 'ContentController@games']);
    Route::get('api/games/{id}', ['uses' => 'ContentController@singleGame']);
    Route::post('api/userAuth', ['uses' => function () {
        $user = \Auth::user();
        if ($user) {
            return \Response::json(['data' => true, 'user' => \Auth::user()]);
        } else {
            return \Response::json(['data' => false]);
        }
    }, 'middleware' => 'simpleauth']);
    Route::get('api/article', ['uses' => 'ContentController@index', 'middleware' => 'simpleauth']);
    Route::post('api/article', ['uses' => 'ContentController@insertArticle', 'middleware' => 'simpleauth']);
    Route::post('api/thought', ['uses' => 'ContentController@insertThought', 'middleware' => 'simpleauth']);
    Route::post('api/game', ['uses' => 'ContentController@insertGame', 'middleware' => 'simpleauth']);
    Route::post('api/project', ['uses' => 'ContentController@insertProject', 'middleware' => 'simpleauth']);
    Route::post('api/article/{id}', ['uses' => 'ContentController@updateArticle', 'middleware' => 'simpleauth']);
    Route::post('api/thought/{id}', ['uses' => 'ContentController@updateThought', 'middleware' => 'simpleauth']);
    Route::post('api/game/{id}', ['uses' => 'ContentController@updateGame', 'middleware' => 'simpleauth']);
    Route::post('api/project/{id}', ['uses' => 'ContentController@updateProject', 'middleware' => 'simpleauth']);
    Route::post('api/image', ['uses' => 'ContentController@image', 'middleware' => 'simpleauth']);

    Route::get('api/memories', ['uses' => 'MemoryController@home']);
    Route::get('api/memories/{id}', ['uses' => 'MemoryController@getMemory']);
    Route::post('api/memories', ['uses' => 'MemoryController@insertMemory', 'middleware' => 'simpleauth']);
    Route::post('api/memories/{id}', ['uses' => 'MemoryController@updateMemory', 'middleware' => 'simpleauth']);

    Route::get('api/meme/home', ['uses' => 'MemeMachineController@home']);
});
