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
			->select('b.display_name')
			->where('a.flg_default', 'Y')
            ->where('a.user_id', $uer_id)
			->first();

		return $result->display_name;
	}

	public static function getStatusAbsen($user_id){
		$notCheckout = DB::table('at_attendance')
			->select('status')
			->where('user_id', $user_id)
            ->where('checkin_datetime', '!=', '')
            ->where('checkout_datetime', '')
			->first();

        $checked = DB::select("SELECT status FROM at_attendance
                                WHERE user_id = $user_id
                                AND SUBSTRING (checkin_datetime, 1, 8) = to_char(current_date, 'YYYYMMDD')
                                AND checkout_datetime != ''
                              ");

		return $notCheckout == null ? ($checked == null ? 'N' : 'Y') : $notCheckout->status;
	}

    public static function getThisWeekMondayDate()
    {
        $monday = DB::select("SELECT to_char(date_trunc('week', current_date), 'YYYYMMDD') AS monday_date");

        return $monday[0]->monday_date;
    }

    public static function getUserIdByUsername($username){
        $result = DB::table('t_user')
            ->select('user_id')
            ->where('username', $username)
            ->first();

        return $result->user_id;
    }

    public static function getUserByUsername($username){
        $result = DB::table('t_user')
            ->select('*')
            ->where('username', $username)
            ->first();

        return $result;
    }
}