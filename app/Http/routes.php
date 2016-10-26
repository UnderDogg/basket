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
        Route::post('merchants/{id}/ips/', 'IpsController@store');
        Route::delete('merchants/{id}/ips/{ip}', 'IpsController@delete');
        Route::get('installations/{id}/edit',  'InstallationsController@edit');
        Route::patch('installations/{id}',       'InstallationsController@update');
        Route::get('installations/{id}/preview-email',  'InstallationsController@previewEmail');
        Route::get('installations/{installation}/products',  'ProductLimitsController@viewProducts');
        Route::post('installations/{installation}/products',  'ProductLimitsController@updateProducts');
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
        Route::get(
            'installations/{id}/applications/pending-cancellations',
            'ApplicationsController@pendingCancellations'
        );
        Route::get('installations/{installation}/applications', 'ApplicationsController@index');
        Route::get('installations/{installation}/applications/{id}', 'ApplicationsController@show');
        Route::post('installations/{installation}/applications/{id}/email', 'ApplicationsController@emailApplication');
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
        Route::get('return-back', 'InitialisationController@returnBack');
        Route::get('locations/{id}/applications/make', 'InitialisationController@prepare');
        Route::get('locations/{id}/applications/assisted', 'InitialisationController@prepareAssisted');
        Route::post('locations/{id}/applications/make', 'InitialisationController@chooseProduct');
        Route::post('locations/{id}/applications/assisted', 'InitialisationController@chooseProductAssisted');
        Route::post('locations/{id}/applications/request', 'InitialisationController@request');
        Route::post('locations/{id}/applications/request-assisted', 'InitialisationController@requestAssisted');
        Route::post('locations/{id}/applications/perform-assisted', 'InitialisationController@performAssisted');

        route::get('locations/{location}/applications/{id}/profile', 'InitialisationController@showProfile');
//        Route::get('locations/{id}/profile', 'InitialisationController@showProfile');
        Route::post('locations/{location}/applications/{id}/profile', 'InitialisationController@createProfilePersonal');
//        Route::post('locations/{id}/profile', 'InitialisationController@createProfilePersonal');

        Route::get('ajax/installations/{installation}/products/{product}/credit-info', 'AjaxController@getCreditInformationForProduct');
        Route::post('ajax/locations/{location}/profile/address', 'AjaxController@addProfileAddress');
        Route::post('ajax/locations/{location}/profile/employment', 'AjaxController@setProfileEmployment');
        Route::post('ajax/locations/{location}/profile/financial', 'AjaxController@setProfileFinancial');
    });

    Route::group(['middleware' => 'permission:applications-merchant-payments'], function () {
        Route::get('installations/{installation}/applications/{id}/add-merchant-payment', 'ApplicationsController@addMerchantPayment');
        Route::post('installations/{installation}/applications/{id}/add-merchant-payment', 'ApplicationsController@processAddMerchantPayment');
    });

    /*
     * Reports
     */
    Route::group(['middleware' => 'permission:reports-view'], function () {
        Route::get('merchants/{merchant}/settlements', 'SettlementsController@index');
        Route::get('merchants/{merchant}/settlements/{id}', 'SettlementsController@settlementReport');
        Route::resource('merchants/{merchant}/partial-refunds', 'PartialRefundsController', [
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
Route::post('push/installations/{id}', 'NotificationsController@catchSynchronisationNotification');
