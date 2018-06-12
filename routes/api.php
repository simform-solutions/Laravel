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

Route::group(['as' => 'api.'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', ['middleware' => ['web'], 'uses' => 'LoginController@login', 'as' => 'login']);
        Route::post('register', ['middleware' => ['web'], 'uses' => 'RegisterController@register', 'as' => 'register']);
        Route::post('check-mobile-number', ['uses' => 'RegisterController@checkMobileNumber', 'as' => 'check-mobile-number']);
        Route::post('verify-mobile-number', ['uses' => 'ForgotPasswordController@sendResetLinkEmail', 'as' => 'verify-mobile-number']);
        Route::post('password/reset', ['middleware' => ['web'], 'uses' => 'ResetPasswordController@reset', 'as' => 'password.reset']);
    });

    Route::group(['middleware' => 'auth.api'], function () {
        Route::get('logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);
        Route::patch('update-profile', ['uses' => 'ProfileController@update', 'as' => 'update-profile']);

        Route::group(['middleware' => 'role:customer', 'as' => 'customer.', 'namespace' => 'Customer'], function () {
            Route::post('restaurants-list', ['uses' => 'RestaurantsController@getList', 'as' => 'restaurants-list']);
        });
    });
});
