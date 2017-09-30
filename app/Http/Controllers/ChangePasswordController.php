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
        if (strlen($newPassword >= 6)){
            $input =[
                'new_password' => $newPassword,
                'current_password' => $currentPassword,
                'userId' => $userId
            ];
            $changePassword = ChangePasswordTransaction::changePassword($input);
            if($changePassword == true){
                return response()->json([
                    'status' => 'OK'
                ]);
            }
            else{
                return response()->json([
                    'status' => 'FAIL',
                    'message' => 'Current Password Invalid'
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'FAIL'
            ]);
        }
    }

}
