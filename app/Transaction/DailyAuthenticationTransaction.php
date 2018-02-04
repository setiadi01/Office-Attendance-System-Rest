<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;
use Ramsey\Uuid\Uuid;

class DailyAuthenticationTransaction
{
    public static function add($input){
        $userId = System::userLoginId();
        $dateTimeNow = System::dateTime();
        $uuid = Uuid::uuid1();
        $generatedDate = $input['generatedDate'];
        $authKeyCheckin = $input['authKeyCheckin'];
        $authDateCheckin = $input['authDateCheckin'];
        $authKeyCheckout = $input['authKeyCheckout'];
        $authDateCheckout = $input['authDateCheckout'];

        DB::table('t_daily_authentication')->insert(
            [
                'user_id' => $userId,
                'secure_key' => $uuid,
                'generated_date' => $generatedDate,
                'auth_key_checkin' => $authKeyCheckin,
                'auth_date_checkin' => $authDateCheckin,
                'auth_key_checkout' => $authKeyCheckout,
                'auth_date_checkout' => $authDateCheckout,
                'version' => 0,
                'create_datetime' => $dateTimeNow,
                'update_datetime' => $dateTimeNow,
                'create_user_id' => $userId,
                'update_user_id' => $userId
            ]);

        $inputForFind = [
            "userId" => $userId,
            "generatedDate" => $generatedDate
        ];

        $output = self::findByIndex($inputForFind);

        return $output;
    }

    public static function update($input){
        $userId = System::userLoginId();
        $dateTimeNow = System::dateTime();
        $id = $input['id'];
        $authKeyCheckin = $input['authKeyCheckin'];
        $authDateCheckin = $input['authDateCheckin'];
        $authKeyCheckout = $input['authKeyCheckout'];
        $authDateCheckout = $input['authDateCheckout'];

        DB::table('t_daily_authentication')
            ->where('daily_authentication_id', $id)
            ->update([
                'auth_key_checkin' => $authKeyCheckin,
                'auth_date_checkin' => $authDateCheckin,
                'auth_key_checkout' => $authKeyCheckout,
                'auth_date_checkout' => $authDateCheckout,
                'version' => DB::raw('version + 1'),
                'update_datetime' => $dateTimeNow,
                'update_user_id' => $userId
            ]);

        $inputForFind = [
            "id" => $id
        ];

        $output = self::findById($inputForFind);

        return $output;
    }

    public static function findByIndex($input){
        $userId = $input['userId'];
        $generatedDate = $input['generatedDate'];

        $result = DB::table('t_daily_authentication')
            ->select('daily_authentication_id', 'user_id', 'secure_key', 'generated_date',
                       'auth_key_checkin', 'auth_date_checkin', 'auth_key_checkout',
                       'auth_date_checkout', 'create_datetime', 'update_datetime',
                       'create_user_id', 'update_user_id', 'version')
            ->where('user_id', $userId)
            ->where('generated_date', $generatedDate)
            ->first();

        return $result;
    }

    public static function findById($input){
        $id = $input['id'];

        $result = DB::table('t_daily_authentication')
            ->select('daily_authentication_id', 'user_id', 'secure_key', 'generated_date',
                'auth_key_checkin', 'auth_date_checkin', 'auth_key_checkout',
                'auth_date_checkout', 'create_datetime', 'update_datetime',
                'create_user_id', 'update_user_id', 'version')
            ->where('daily_authentication_id', $id)
            ->first();

        return $result;
    }
}