<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;


class AttendanceTransaction
{
    public static function add($input){
        $userId = System::userLoginId();
        $dateTimeNow = System::dateTime();
        $dailyAuthenticationId = $input['dailyAuthenticationId'];
        $checkinDatetime = $input['checkinDatetime'];
        $checkoutDatetime = $input['checkoutDatetime'];
        $status = $input['status'];

        DB::table('at_attendance')->insert([
            'user_id' => $userId,
            'daily_authentication_id' => $dailyAuthenticationId,
            'checkin_datetime' => $checkinDatetime,
            'checkout_datetime' => $checkoutDatetime,
            'status' => $status,
            'version' => 0,
            'create_datetime' => $dateTimeNow,
            'update_datetime' => $dateTimeNow,
            'create_user_id' => $userId,
            'update_user_id' => $userId
        ]);

        $inputForFind = [
            "userId" => $userId,
            "dailyAuthenticationId" => $dailyAuthenticationId
        ];

        $output = self::findByIndex($inputForFind);

        return $output;

    }

    public static function findByIndex($input){
        $userId = $input['userId'];
        $dailyAuthenticationId = $input['dailyAuthenticationId'];

        $result = DB::table('at_attendance')
            ->select('attendance_id', 'user_id', 'daily_authentication_id',
                        'checkin_datetime', 'checkout_datetime', 'status',
                        'version', 'create_datetime', 'create_user_id',
                        'update_datetime', 'update_user_id')
            ->where('user_id', $userId)
            ->where('daily_authentication_id', $dailyAuthenticationId)
            ->first();

        return $result;
    }

}