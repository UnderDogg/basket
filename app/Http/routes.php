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
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('logout', 'Auth\AuthController@getLogout');

   $cont = explode('/', $_SERVER['REQUEST_URI']);
   $contLo = $cont[1];
   $contUp = ucwords($cont[1]) . 'Controller@';
   if (!empty($contLo) && $contLo != 'login') {
       Route::get(     $contLo,               $contUp . 'index'    );
       Route::get(     $contLo.'/create',     $contUp . 'create'   );
       Route::post(    $contLo,               $contUp . 'store'    );
       Route::get(     $contLo.'/{id}',       $contUp . 'show'     );
       Route::delete(  $contLo.'/{id}',       $contUp . 'destroy'  );
       Route::get(     $contLo.'/{id}/edit',  $contUp . 'edit'     );
       Route::patch(   $contLo.'/{id}',       $contUp . 'update'   );
   }
});
