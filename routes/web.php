<?php

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

Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('managers', 'ManagersController');
        Route::get('check-email/{manager?}', ['as' => 'managers.checkEmail', 'uses' => 'ManagersController@checkEmail']);
        Route::get('check-mobile/{manager?}', ['as' => 'managers.checkMobile', 'uses' => 'ManagersController@checkMobile']);
        Route::get('managers-list', ['as' => 'managers.anyData', 'uses' => 'ManagersController@anyData']);
    });
});
