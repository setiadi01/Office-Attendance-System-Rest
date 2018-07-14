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

	    // UPDATE status menjadi O (checkout) apabila sudah berganti hari
        DB::select(" UPDATE at_attendance A SET status = 'O',
                  checkout_datetime = '-',
                  update_datetime = to_char(current_timestamp, 'YYYYMMDDHH24MISS'),
                  update_user_id = $user_id ,
                  version = A.version+1
                  WHERE A.user_id = $user_id
                  AND to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'YYYYMMDD') < to_char(current_date, 'YYYYMMDD')
                  AND checkout_datetime = ''
                ");

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

    public static function isThisIpCanHavePermission($ip, $rules){

        $count  = DB::select("SELECT COUNT(1) as count
                        FROM t_ip_filtering
                        WHERE UPPER('$ip') LIKE UPPER('%'||ip_address||'%')
                        AND rules = '$rules'
                    ");

        return ($count[0]->count !=null && $count[0]->count > 0) ? true : false;
    }
}