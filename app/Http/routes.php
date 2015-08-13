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
    Route::group(['middleware' => 'permission:users-management'], function () {
        Route::get(   'users/create',           'UsersController@create');
        Route::get(   'users/{id}/delete',      'UsersController@delete');
        Route::post(  'users',                  'UsersController@store');
        Route::delete('users/{id}',             'UsersController@destroy');
        Route::get(   'users/{id}/edit',        'UsersController@edit');
        Route::patch( 'users/{id}',             'UsersController@update');
        Route::get(   'users/{id}/locations',   'UsersController@editLocations');
        Route::patch( 'users/{id}/locations',    'UsersController@updateLocations');
    });

    Route::group(['middleware' => 'permission:users-view'], function () {
        Route::get(   'users',                  'UsersController@index');
        Route::get(   'users/{id}',             'UsersController@show');
    });

    /*
     * Roles
     */
    Route::group(['middleware' => 'permission:roles-management'], function () {
        Route::get('roles/{id}/delete', 'RolesController@delete');
        Route::get('roles/create', 'RolesController@create');
        Route::post('roles', 'RolesController@store');
        Route::delete('roles/{id}', 'RolesController@destroy');
        Route::get('roles/{id}/edit', 'RolesController@edit');
        Route::patch('roles/{id}', 'RolesController@update');
    });

    Route::group(['middleware' => 'permission:roles-view'], function () {
        Route::get('roles', 'RolesController@index');
        Route::get('roles/{id}', 'RolesController@show');
    });

    /*
     * Merchants & Installations
     */
    Route::group(['middleware' => 'role:su'], function () {
        Route::get('merchants/create', 'MerchantsController@create');
        Route::post('merchants', 'MerchantsController@store');
        Route::delete('merchants/{id}', 'MerchantsController@destroy');
    });

    Route::group(['middleware' => 'permission:merchants-view'], function () {
        Route::get('merchants', 'MerchantsController@index');
        Route::get('merchants/{id}', 'MerchantsController@show');
        Route::get('installations',            'InstallationsController@index');
        Route::get('installations/{id}',       'InstallationsController@show');
    });

    Route::group(['middleware' => 'permission:merchants-management'], function () {
        Route::get('merchants/{id}/edit', 'MerchantsController@edit');
        Route::patch('merchants/{id}', 'MerchantsController@update');
        Route::get('merchants/{id}/synchronise', 'MerchantsController@synchronise');
        Route::get('merchants/{id}/installations/synchronise', 'InstallationsController@synchroniseAllForMerchant');
        Route::get('merchants/{id}/ips', 'IpsController@index');
        Route::post('merchants/{id}/ips/', ['id' => 'id', 'uses' => 'IpsController@store']);
        Route::delete('merchants/{id}/ips/{ip}', ['id' => 'id', 'ip' => 'ip', 'uses' => 'IpsController@delete']);
        Route::get('installations/{id}/edit',  'InstallationsController@edit');
        Route::patch('installations/{id}',       'InstallationsController@update');
    });

    /*
     * Locations
     */
    Route::group(['middleware' => 'permission:locations-management'], function () {
        Route::get('locations/{id}/delete', 'LocationsController@delete');
        Route::get('locations/create', 'LocationsController@create');
        Route::post('locations', 'LocationsController@store');
        Route::delete('locations/{id}', 'LocationsController@destroy');
        Route::get('locations/{id}/edit', 'LocationsController@edit');
        Route::patch('locations/{id}', 'LocationsController@update');
    });

    Route::group(['middleware' => 'permission:locations-view'], function () {
        Route::get('locations', 'LocationsController@index');
        Route::get('locations/{id}', 'LocationsController@show');
    });

    /*
     * Applications
     */
    Route::group(['middleware' => 'permission:applications-view'], function () {
        Route::get('installations/{installation}/applications', 'ApplicationsController@index');
        Route::get('installations/{installation}/applications/{id}', 'ApplicationsController@show');
        Route::get(
            'installations/{id}/applications/pending-cancellations',
            'ApplicationsController@pendingCancellations'
        );
    });

    Route::group(['middleware' => 'permission:applications-fulfil'], function () {
        Route::get(
            'installations/{installation}/applications/{id}/fulfil',
            'ApplicationsController@confirmFulfilment'
        );
        Route::post('installations/{installation}/applications/{id}/fulfil', 'ApplicationsController@fulfil');
    });

    Route::group(['middleware' => 'permission:applications-cancel'], function () {
        Route::get('installations/{installation}/applications/{id}/request-cancellation', 'ApplicationsController@confirmCancellation');
        Route::post('installations/{installation}/applications/{id}/request-cancellation', 'ApplicationsController@requestCancellation');
    });

    Route::group(['middleware' => 'permission:applications-refund'], function () {
        Route::get('installations/{installation}/applications/{id}/partial-refund', 'ApplicationsController@confirmPartialRefund');
        Route::post('installations/{installation}/applications/{id}/partial-refund', 'ApplicationsController@requestPartialRefund');
    });

    Route::group(['middleware' => 'permission:applications-make'], function () {
        Route::get('locations/{id}/applications/make', 'InitialisationController@prepare');
        Route::post('locations/{id}/applications/make', 'InitialisationController@chooseProduct');
        Route::post('locations/{id}/applications/confirm', 'InitialisationController@confirm');
        Route::post('locations/{id}/applications/request', 'InitialisationController@request');
    });

    /*
     * Reports
     */
    Route::group(['middleware' => 'permission:reports-view'], function () {
        Route::get('settlements', 'SettlementsController@index');
        Route::get('settlements/{id}', 'SettlementsController@settlementReport');
        Route::resource('partial-refunds', 'PartialRefundsController', [
            'only' => ['index', 'show'],
        ]);
    });

    /*
     * Account
     */
    Route::get('account', 'AccountController@show');
    Route::get('account/edit', 'AccountController@edit');
    Route::post('account/edit', 'AccountController@update');
    Route::post('account/edit/password', 'AccountController@changePassword');


});

    Route::post('push/installations/{id}/catch-notification', 'NotificationsController@catchNotification');
