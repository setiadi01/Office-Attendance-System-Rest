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

Route::post('login', 'Auth\ApiAuthController@login');
Route::get('get-profie-picture/{username}', 'Auth\ApiAuthController@getProfilePicture');

Route::get('images/{image}', 'FileController@loadImage');

Route::group(['middleware' => ['auth:api', 'role:supervisor|employee']], function(){	

	Route::get('get-logged-user', 'ExampleController@getLoggedUser');

});