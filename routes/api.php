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

Route::group(['as' => 'api.'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', ['middleware' => ['web'], 'uses' => 'LoginController@login', 'as' => 'login']);
        Route::post('fb-login', ['middleware' => ['web'], 'uses' => 'LoginController@fbLogin', 'as' => 'fb-login']);
        Route::post('register', ['middleware' => ['web'], 'uses' => 'RegisterController@register', 'as' => 'register']);
        Route::post('check-mobile-number', ['uses' => 'RegisterController@checkMobileNumber', 'as' => 'check-mobile-number']);
        Route::post('password/email', ['uses' => 'ForgotPasswordController@sendResetLinkEmail', 'as' => 'password.email']);
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
