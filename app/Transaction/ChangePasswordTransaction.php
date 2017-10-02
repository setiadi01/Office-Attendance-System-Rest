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

        $update = DB::table('t_user')
            ->where('user_id', $userId)
            ->update([	'password' => $newPassword,
                'update_datetime' => System::dateTime(),
                'version' => DB::raw('version + 1')]);

        return $update;

    }

    public static function valCurrentPassword($input){
        $userId = $input['userId'];
        $currentPassword = bcrypt($input['current_password']);
        $userPassword  = DB::table('t_user')
            ->select('password')
            ->where('user_id', $userId)
            ->first();

        $result = password_verify($input['current_password'],$userPassword->password);

        return $result==null||$result==''?0:$result;

    }

}