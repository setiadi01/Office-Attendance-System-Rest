<?php

namespace App\Http\Controllers;

use App\System\System;
use Illuminate\Support\Facades\Auth;
use App\Transaction\SystemTransaction;
use App\Transaction\ApiAuthTransaction;
use App\Transaction\EditProfileTransaction;

/**
 * @author  Setiadi, 20 Agustus 2017
 */

class LoggedController extends Controller
{
	/**
	 * Mengembalikan data user yang login
	 * @return Json
	 */
    public function getLoggedUser(){

        $userId = System::userLoginId();
        $role = System::defaultRole($userId);
        $username = System::userUsername();
        $getStatus = SystemTransaction::getStatusAbsen($userId);
        $profilePicture = ApiAuthTransaction::getProfilePicture($username);
        $personProfile = EditProfileTransaction::getPersonProfile($userId);

        $user = Auth::user();
        $data['username'] = $user->username;
        $data['full_name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['role'] = $role;
        $data['profile_picture'] = $profilePicture==null?'':$profilePicture;
        $data["phone_number"] = $personProfile->mobile_no;
        $data["checkStatus"] = $getStatus;

    	return response()->json([
    		'status' => 'OK',
    		'data' => $data
    	]);
    }
}
