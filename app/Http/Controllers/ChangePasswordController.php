<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\ChangePasswordTransaction;

/**
 * @author  Cong, 27 September 2017
 */

class ChangePasswordController extends Controller
{

    public function changePassword(Request $request){

        $userId = System::userLoginId();
        $inputData = $request->all();
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
