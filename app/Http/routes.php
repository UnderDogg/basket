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

Route::group(['middleware' => 'guest'], function () {

    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', 'Auth\AuthController@postLogin');

    // Password reset link request routes...
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'LandingController@index');

    Route::get('logout', 'Auth\AuthController@getLogout');

    /*
     * Users
     */
    Route::group(['middleware' => 'permission:user-management'], function () {
        Route::get(   'users',             'UsersController@index');
        Route::get(   'users/create',      'UsersController@create');
        Route::get(   'users/{id}/delete', 'UsersController@delete');
        Route::post(  'users',             'UsersController@store');
        Route::get(   'users/{id}',        'UsersController@show');
        Route::delete('users/{id}',        'UsersController@destroy');
        Route::get(   'users/{id}/edit',   'UsersController@edit');
        Route::patch( 'users/{id}',        'UsersController@update');
    });

    /*
     * Roles
     */
    Route::get(   'roles',             'RolesController@index');
    Route::get(   'roles/{id}/delete', 'RolesController@delete');
    Route::get(   'roles/create',      'RolesController@create');
    Route::post(  'roles',             'RolesController@store');
    Route::get(   'roles/{id}',        'RolesController@show');
    Route::delete('roles/{id}',        'RolesController@destroy');
    Route::get(   'roles/{id}/edit',   'RolesController@edit');
    Route::patch( 'roles/{id}',        'RolesController@update');

    /*
     * Merchants
     */
    Route::get(   'merchants',                          'MerchantsController@index');
    Route::get(   'merchants/create',                   'MerchantsController@create');
    Route::post(  'merchants',                          'MerchantsController@store');
    Route::get('merchants/{id}',                    'MerchantsController@show');
    Route::delete('merchants/{id}',                 'MerchantsController@destroy');
    Route::get('merchants/{id}/edit',               'MerchantsController@edit');
    Route::patch('merchants/{id}',                  'MerchantsController@update');
    Route::get('merchants/{id}/synchronise',        'MerchantsController@synchronise');
    Route::get('merchants/{id}/installations/synchronise',  'InstallationsController@synchroniseAllForMerchant');

    /*
     * Locations
     */
    Route::get(   'locations',             'LocationsController@index');
    Route::get(   'locations/{id}/delete', 'LocationsController@delete');
    Route::get(   'locations/create',      'LocationsController@create');
    Route::post(  'locations',             'LocationsController@store');
    Route::get(   'locations/{id}',        'LocationsController@show');
    Route::delete('locations/{id}',        'LocationsController@destroy');
    Route::get(   'locations/{id}/edit',   'LocationsController@edit');
    Route::patch( 'locations/{id}',        'LocationsController@update');

    /*
     * Installations
     */
    Route::get(   'installations',            'InstallationsController@index');
    Route::get(   'installations/{id}',       'InstallationsController@show');
    Route::get(   'installations/{id}/edit',  'InstallationsController@edit');
    Route::patch( 'installations/{id}',       'InstallationsController@update');

    /*
     * Applications
     */
    Route::get(   'applications',            'ApplicationsController@index');
    Route::get(   'applications/{id}',       'ApplicationsController@show');
    Route::get(   'applications/{id}/edit',  'ApplicationsController@edit');
    Route::patch( 'applications/{id}',       'ApplicationsController@update');
    Route::get(   'applications/{id}/fulfil',   'ApplicationsController@confirmFulfilment');
    Route::post( 'applications/{id}/fulfil',   'ApplicationsController@fulfil');
    Route::get(   'applications/{id}/request-cancellation', 'ApplicationsController@confirmCancellation');
    Route::post(  'applications/{id}/request-cancellation', 'ApplicationsController@requestCancellation');

    /*
     * Settlements
     */
    Route::get('settlements', 'SettlementsController@index');
    Route::get('settlements/{id}', 'SettlementsController@settlementReport');

});

Route::post('push/installations/{id}/catch-notification', 'NotificationsController@catchNotification');
