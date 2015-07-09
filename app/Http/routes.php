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

});
