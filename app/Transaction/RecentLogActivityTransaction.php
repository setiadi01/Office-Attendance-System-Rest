<?php
/**
 * Created by PhpStorm.
 * User: Cong
 * Date: 30/09/17
 * Time: 23:45
 */

namespace App\Transaction;

use DB;
use App\System\System;

class RecentLogActivityTransaction
{
    public static function generateLogActivity($input){

        $userId = $input["userId"];
        $refId = $input["refId"];
        $message = $input["message"];
        $type = $input["type"];
        $intRemark = $input["intRemark"];

        $inputValLog = [
            "userId" => $userId,
            "type" => $type
        ];
        $valCanInputLog = static::canInputLog($inputValLog);

        if($valCanInputLog < 1 ) {
            DB::table('t_recent_log_acitivity')->insert([
                [
                    'user_id' => $userId,
                    'ref_id' => $refId,
                    'activity_datetime' => System::dateTime(),
                    'message' => $message,
                    'type' => $type,
                    'int_remark' => $intRemark,
                    'version' => 0,
                    'create_datetime' => System::dateTime(),
                    'update_datetime' => System::dateTime(),
                    'create_user_id' => $userId,
                    'update_user_id' => $userId
                ]]);
        }
    }

    public static function getRecentActivityList($input){
        $userId = $input['userId'];
        $limit = $input['limit'];
        $offset = $input['offset'];

        $list  = DB::select("SELECT B.full_name,
                                to_char(to_timestamp(A.activity_datetime, 'YYYYMMDDHH24MISS'), 'FMDay') activity_day, 
                                to_char(to_timestamp(A.activity_datetime, 'YYYYMMDDHH24MISS'), 'DD') activity_date, 
                                to_char(to_timestamp(A.activity_datetime, 'YYYYMMDDHH24MISS'), 'FMMonth') activity_month, 
                                MAX(to_char(to_timestamp(A.activity_datetime, 'YYYYMMDDHH24MISS'), 'HH24.MI')) activity_hours, 
                                SUBSTRING(A.activity_datetime, 1, 11) ordered,
                                A.message FROM t_recent_log_acitivity A
                                INNER JOIN t_user B ON A.user_id = B.user_id
                                WHERE A.user_id = $userId 
                                AND SUBSTRING(A.activity_datetime,1,8) BETWEEN to_char(to_timestamp(A.activity_datetime, 'YYYYMMDDHH24MISS') - interval '30' day, 'YYYYMMDD') AND to_char(current_date, 'YYYYMMDD')
                                GROUP BY B.full_name, A.message, activity_day, activity_date, activity_month, ordered
                                ORDER BY ordered DESC  
                                limit $limit offset $offset
                            ");

        return [
            "recentActivityList" => $list
        ];


    }

    public static function canInputLog($input){

        $userId = $input["userId"];
        $type = $input["type"];
        $currentDateTime = System::dateTime();

        $result = DB::select("SELECT count(1) AS activity FROM t_recent_log_acitivity
                              WHERE user_id = $userId
                              AND type = '$type'
                              AND SUBSTRING(activity_datetime,1,11) = SUBSTRING('$currentDateTime',1,11)
                            ");

        return $result[0]->activity;

        return $result == null ? 0 : $result->activity;
    }
}