<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;

class ReasonTransaction
{
    public static function findByIndex($input){
        $reasonCode = $input['reasonCode'];

        $result = DB::table('t_reason')
            ->select('reason_id', 'reason_code', 'reason_name', 'description',
                        'version', 'create_datetime', 'create_user_id',
                        'update_datetime', 'update_user_id', 'active',
                        'active_datetime', 'non_active_datetime')
            ->where('reason_code', $reasonCode)
            ->first();

        return $result;
    }

}