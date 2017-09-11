<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;

/**
* @author Setiadi, 10 September 2017
*/

class ApiAuthTransaction
{
	
	public static function getProfilePicture($username){
		$result = DB::table('t_user as a')
			->join('m_employee as b', 'b.user_id', '=', 'a.user_id')
			->join('m_employee_additional_info as c', 'c.employee_id', '=', 'b.employee_id')
			->select('c.profile_picture')
			->where('a.username', $username)
			->first();

		return $result == null ? null : $result->profile_picture;
	}
}