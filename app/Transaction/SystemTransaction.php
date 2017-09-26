<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;


/**
* @author Setiadi, 12 Agustus 2017
*/

class SystemTransaction
{
	
	public static function roleName($uer_id){
		$result = DB::table('t_role_user as a')
			->join('t_role as b', 'b.role_id', '=', 'a.role_id')
			->select('b.name')
			->where('a.flg_default', 'Y')
			->first();

		return $result->name;
	}

	public static function getStatusAbsen($user_id){
		$now = System::date();
		$result = DB::table('at_attendance')
			->select('status')
			->where('user_id', $user_id)
			->where('checkin_datetime', $now)
			->first();

		return $result == null ? null : $result->status;
	}
}