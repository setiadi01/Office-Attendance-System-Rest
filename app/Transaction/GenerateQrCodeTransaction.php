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
	
	public static function getUuid(){
		$userId = System::userLoginId();
		$getUuid = DB::SELECT("
			SELECT uuid
			FROM t_daily_authentication_seq
			WHERE user_id = $userId;
		");

		return $getUuid[0]->uuid;
	}
}