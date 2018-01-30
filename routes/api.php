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
Route::post('loginWeb', 'Auth\ApiAuthController@loginWeb');

Route::get('get-profie-picture/{username}', 'Auth\ApiAuthController@getProfilePicture');
Route::get('get-qrcode/{username}/{userId}', 'GenerateQrCodeController@getQrCode');

Route::get('images/{image}', 'FileController@loadImage');

Route::post('checkin', 'AttendanceController@checkin');
Route::post('checkout', 'AttendanceController@checkout');
Route::post('change-password', 'ChangePasswordController@changePassword');
Route::post('edit-profile', 'EditProfileController@editProfile');

Route::group(['middleware' => ['auth:api']], function(){

    Route::get('get-logged-user', 'LoggedController@getLoggedUser');
    Route::get('get-info-edit-profile', 'EditProfileController@getUser');
    Route::get('check-username', 'EditProfileController@checkUsername');
    Route::get('get-report-absen', 'AttendanceController@getReportAbsen');
    Route::get('get-recent-activity', 'RecentActivityController@getRecentActivity');
    Route::get('get-last-info-checkin', 'AttendanceController@getLastInfoCheckin');
    Route::get('get-summary-chart', 'AttendanceController@getSummaryChart');
    Route::post('get-detail-checkin-list', 'DashboardController@getDetailCheckinList');
    Route::post('get-summary-checkin-list', 'DashboardController@getSummaryCheckinList');


});