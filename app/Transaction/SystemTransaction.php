<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;

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
}