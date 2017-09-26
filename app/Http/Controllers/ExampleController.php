<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use Illuminate\Support\Facades\Auth;
use App\Transaction\SystemTransaction;

/**
 * @author  Setiadi, 20 Agustus 2017
 */

class ExampleController extends Controller
{
	/**
	 * Mengembalikan data user yang login
	 * @return Json
	 */
    public function getLoggedUser(){
    	return response()->json([
    		'status' => 'OK',
    		'data' => Auth::user()
    	]);
    }

	public function getStatusAbsen(){
		$userId = System::userLoginId();
		$username = System::userUsername();
		$getStatus = SystemTransaction::getStatusAbsen($userId);
		return response()->json([
			'status' => 'OK',
			'data' => $getStatus
		]);
	}
}
