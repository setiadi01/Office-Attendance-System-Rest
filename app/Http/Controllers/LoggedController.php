<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use Illuminate\Support\Facades\Auth;
use App\Transaction\SystemTransaction;

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

        $user = Auth::user();
        $data['username'] = $user->username;
        $data['full_name'] = $user->full_name;
        $data['role'] = $role;
        $data['profile_picture'] = $user->profile_picture==null?'':$user->profile_picture;
        $data["checkStatus"] = $getStatus;

    	return response()->json([
    		'status' => 'OK',
    		'data' => $data
    	]);
    }
}
