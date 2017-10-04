<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;

/**
* @author Cong, 27 September 2017
*/

class EditProfileTransaction
{

    public static function getPersonProfile($user_id){
        $result = DB::table('t_user as A')
            ->join('m_employee as B', 'A.user_id', '=', 'B.user_id')
            ->join('m_person_profile as C', 'B.person_id', '=', 'C.person_id')
            ->select('C.prefix_title', 'C.name', 'C.sufix_title',
                'C.pob', 'C.dob', 'C.religin', 'C.marital_status',
                'C.sex', 'C.blood_type', 'C.email_address',
                'C.mobile_no', 'C.current_address', 'C.city',
                'C.province')
            ->where('A.user_id', $user_id)
            ->first();
        return $result == null ? null : $result;
    }

    public static function valUsername($inputArray){
        $result = DB::table('t_user as A')
            ->where('A.user_id', '!=', $inputArray["userId"])
            ->where('A.username', $inputArray["username"])
            ->count();
        return $result == null ? 0 : $result;
    }

	public static function editProfile($inputArray){
		DB::table('t_user')
					->where('user_id', $inputArray["userId"])
					->update([	'full_name' => $inputArray["fullName"],
								'username' => $inputArray["username"],
								'update_datetime' => System::dateTime(),
								'version' => DB::raw('version + 1')]);
	}

}