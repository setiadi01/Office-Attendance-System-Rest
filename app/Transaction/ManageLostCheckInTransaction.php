<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;

class ManageLostCheckInTransaction
{
    public static function add($input){
        $userId = System::userLoginId();
        $dateTimeNow = System::dateTime();
        $checkInDate = $input['checkinDate'];
        $reasonId = $input['reasonId'];

        DB::table('t_manage_lost_checkin')->insert(
            [
                'user_id' => $userId,
                'checkin_date' => $checkInDate,
                'reason_id' => $reasonId,
                'remark' => '',
                'version' => 0,
                'create_datetime' => $dateTimeNow,
                'update_datetime' => $dateTimeNow,
                'create_user_id' => $userId,
                'update_user_id' => $userId
            ]);

        $inputForFind = [
            "userId" => $userId,
            "checkinDate" => $checkInDate
        ];

        $output = self::findByIndex($inputForFind);

        return $output;
    }

    public static function findByIndex($input){
        $userId = $input['userId'];
        $checkInDate = $input['checkinDate'];

        $result = DB::table('t_manage_lost_checkin')
            ->select('manage_lost_checkin_id', 'user_id',
                        'checkin_date', 'reason_id', 'remark',
                        'version', 'create_datetime', 'create_user_id',
                        'update_datetime', 'update_user_id')
            ->where('user_id', $userId)
            ->where('checkin_date', $checkInDate)
            ->first();

        return $result;
    }
}