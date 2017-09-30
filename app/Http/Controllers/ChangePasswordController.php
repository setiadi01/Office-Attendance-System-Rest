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
        $password = $inputData['password'];
        if (strlen($password >= 6)){
            $input =[
                'password' => $password,
                'userId' => $userId
            ];
            $changePassword = ChangePasswordTransaction::changePassword($input);

            return response()->json([
                'status' => 'OK'
            ]);
        }
        else{
            return response()->json([
                'status' => 'FAIL'
            ]);
        }
    }

}
