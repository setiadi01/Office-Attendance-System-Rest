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
        $username = System::userUsername();
        $getStatus = SystemTransaction::getStatusAbsen($userId);

        $data = Auth::user();
        $data["checkStatus"] = $getStatus;

    	return response()->json([
    		'status' => 'OK',
    		'data' => $data
    	]);
    }
}
