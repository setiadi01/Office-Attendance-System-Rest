<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\ChangePasswordTransaction;
use App\Transaction\RecentLogActivityTransaction;
use App\Transaction\SystemTransaction;

/**
 * @author  Cong, 27 September 2017
 */

class ChangePasswordController extends Controller
{

    public function changePassword(Request $request){

        $inputData = $request->all();
        $userId = SystemTransaction::getUserIdByUsername($inputData['username']);
        $newPassword = $inputData['newPassword'];
        $currentPassword = $inputData['currentPassword'];

        $input =[
            'new_password' => $newPassword,
            'current_password' => $currentPassword,
            'userId' => $userId
        ];

        $valPassword = ChangePasswordTransaction::valCurrentPassword($input);

        if($valPassword>0) {

            if (strlen($newPassword) >= 6) {

                $insertLog = [
                    "userId" => $userId,
                    "refId" => -99,
                    "message" => 'You has successfully to change password',
                    "type" => 'CHANGE_PASSWORD',
                    "intRemark" => ''
                ];

                RecentLogActivityTransaction::generateLogActivity($insertLog);

                ChangePasswordTransaction::changePassword($input);
                return response()->json([
                    'status' => 'OK',
                    'message' => 'Your password was changed'
                ]);

            } else {
                return response()->json([
                    'status' => 'FAIL',
                    'message' => 'Password must be at least 6 character'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'Current password is invalid'
            ]);
        }
    }

}
