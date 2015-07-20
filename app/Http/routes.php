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

    // User CRUD Routes
    Route::get(   'user',            'UserController@index'    );
    Route::get(   'user/create',     'UserController@create'   );
    Route::post(  'user',            'UserController@store'    );
    Route::get(   'user/{id}',       'UserController@show'     );
    Route::delete('user/{id}',       'UserController@destroy'  );
    Route::get(   'user/{id}/edit',  'UserController@edit'     );
    Route::patch( 'user/{id}',       'UserController@update'   );

    Route::get(   'role',            'RoleController@index'    );
    Route::get(   'role/create',     'RoleController@create'   );
    Route::post(  'role',            'RoleController@store'    );
    Route::get(   'role/{id}',       'RoleController@show'     );
    Route::delete('role/{id}',       'RoleController@destroy'  );
    Route::get(   'role/{id}/edit',  'RoleController@edit'     );
    Route::patch( 'role/{id}',       'RoleController@update'   );

    Route::get(   'merchants',            'MerchantsController@index'    );
    Route::get(   'merchants/create',     'MerchantsController@create'   );
    Route::post(  'merchants',            'MerchantsController@store'    );
    Route::get(   'merchants/{id}',       'MerchantsController@show'     );
    Route::delete('merchants/{id}',       'MerchantsController@destroy'  );
    Route::get(   'merchants/{id}/edit',  'MerchantsController@edit'     );
    Route::patch( 'merchants/{id}',       'MerchantsController@update'   );

    Route::get(   'installations',            'InstallationsController@index'    );
    Route::get(   'installations/{id}',       'InstallationsController@show'     );
    Route::get(   'installations/{id}/edit',  'InstallationsController@edit'     );
    Route::patch( 'installations/{id}',       'InstallationsController@update'   );
});
