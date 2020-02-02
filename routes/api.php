<?php

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

Route::group(['middleware' => ['guest:api']], function() {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('login/refresh', 'Auth\LoginController@refresh');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('register', 'Auth\RegisterController@register');
    Route::prefix('/guest/film')->group(function () {
        Route::get('all', 'FilmsController@index');
        Route::post('getFilmBySlug', 'FilmsController@getFilmBySlug');
        Route::post('getFilmComments', 'FilmsController@getFilmComments');
    });
});

Route::group(['middleware' => ['jwt']], function() {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('me', 'Auth\LoginController@me');
    Route::put('profile', 'ProfileController@update');

    // ----- film entity related api's
    Route::prefix('/film')->group(function () {

        Route::post('create', 'FilmsController@store');
        Route::post('create_comment', 'FilmsController@createComment');
        Route::get('all', 'FilmsController@index');
        Route::post('getFilmBySlug', 'FilmsController@getFilmBySlug');
        Route::post('getFilmComments', 'FilmsController@getFilmComments');
    });

});

