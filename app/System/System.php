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

    public static function userUsername(){
        return Auth::user()->username;
    }

    public static function userFullName(){
        return Auth::user()->full_name;
    }

    public static function userEmail(){
        return Auth::user()->email;
    }

	public static function dateTime(){
		return date('YmdHis', time());
	}

	public static function dateTimeForQrCode(){
		return date('YmdHi', time());
	}

	public static function date(){
		return date('Ymd', time());
	}

    public static function yearNow(){
        return date('Y', time());
    }

    public static function monthNow(){
        return date('m', time());
    }

    public static function dayNow(){
        return date('d', time());
    }

	public static function defaultRole(){
		return SystemTransaction::roleName(Auth::user()->user_id);
	}

    public static function ipHandler($ip, $rules){
        return SystemTransaction::isThisIpCanHavePermission($ip, $rules);
    }

	public static function roleAdmin(){
		return "admin";
	}

    public static function currentVersion(){
        return "BETA-0.0.5";
    }
	
}