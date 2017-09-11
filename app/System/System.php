<?php

namespace App\System;

use Illuminate\Support\Facades\Auth;
use App\Transaction\SystemTransaction;

/**
*  
* @author  Setiadi, 20 Agustus 2017
*/

class System
{
	public static function userLoginId(){
		return Auth::user()->user_id;
	}

	public static function dateTime(){
		return date('YmdHis', time());
	} 

	public static function date(){
		return date('Ymd', time());
	}

	public static function defaultRole(){
		return SystemTransaction::roleName(Auth::user()->user_id);
	}
	
}