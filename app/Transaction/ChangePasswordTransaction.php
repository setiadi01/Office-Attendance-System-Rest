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
        $password = $input['password'];

        DB::table('t_user')
            ->where('user_id', $userId)
            ->update([	'password' => bcrypt($password),
                'update_datetime' => System::dateTime(),
                'version' => DB::raw('version + 1')]);
    }

}