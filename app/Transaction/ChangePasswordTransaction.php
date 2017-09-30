<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\System\System;

/**
* @author Cong, 27 September 2017
*/

class ChangePasswordTransaction
{

    public static function changePassword($input){
        $userId = $input['userId'];
        $newPassword = bcrypt($input['new_password']);
        $currentPassword = bcrypt($input['current_password']);

        $lateToCheckIn  = DB::select("SELECT count(*) AS user FROM t_user A
								WHERE A.user_id = $userId 
								AND password = '$currentPassword'");

        if($lateToCheckIn[0]->user > 0){
            DB::table('t_user')
                ->where('user_id', $userId)
                ->update([	'password' => $newPassword,
                    'update_datetime' => System::dateTime(),
                    'version' => DB::raw('version + 1')]);
            $update = true;
            return $update;
        }
        else{
            $update = false;
            return $update;
        }


    }

}