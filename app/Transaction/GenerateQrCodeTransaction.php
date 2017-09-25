<?php

namespace App\Transaction;

use Ramsey\Uuid\Uuid;
use DB;
use App\System\System;

/**
* @author Setiadi, 10 September 2017
*/

class GenerateQrCodeTransaction
{
	
	public static function getUuid($userId){
		$now = System::date();
		$getUuid = DB::SELECT("
			SELECT secure_key
			FROM t_daily_authentication
			WHERE user_id = $userId AND generated_date = '$now' ;
		");

		return  $getUuid == null ? null : $getUuid[0]->secure_key;
	}
}