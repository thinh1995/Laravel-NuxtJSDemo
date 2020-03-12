<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::get('/user', 'AuthController@user');
Route::post('/logout', 'AuthController@logout');

Route::group(['prefix' => 'topics'], function() {
    Route::post('/', 'TopicController@store')->middleware('auth:api');
    Route::get('/', 'TopicController@index');
    Route::get('/{topic}', 'TopicController@show');
    Route::patch('/{topic}', 'TopicController@update')->middleware('auth:api');
    Route::delete('/{topic}', 'TopicController@destroy')->middleware('auth:api');

    // Post routes group
    Route::group(['prefix' => '/{topic}/posts'], function() {
        Route::get('/{post}', 'PostController@show');
        Route::post('/', 'PostController@store')->middleware('auth:api');
        Route::patch('/{post}', 'PostController@update')->middleware('auth:api');
        Route::delete('/{post}', 'PostController@destroy')->middleware('auth:api');

        // Like routte
        Route::group(['prefix' => '{post}/likes'], function() {
            Route::post('/', 'PostLikeController@store')->middleware('auth:api');
        });
    });
});
