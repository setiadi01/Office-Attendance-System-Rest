<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\EditProfileTransaction;
use App\Transaction\RecentLogActivityTransaction;

/**
 * @author  Cong, 27 September 2017
 */

class EditProfileController extends Controller
{

    public function getUser(){

        $userId = System::userLoginId();
        $fullName = System::userFullName();
        $username = System::userUsername();
        $email = System::userEmail();
        $personProfile = EditProfileTransaction::getPersonProfile($userId);

        $data["full_name"] = $fullName;
        $data["username"] = $username;
        $data["email"] = $email;
        $data["phone_number"] = $personProfile->mobile_no;


        return response()->json([
            'status' => 'OK',
            'data' => $data
        ]);
    }

    public function checkUsername(Request $request){

        $userId = System::userLoginId();
        $inputData  = $request->all();


        $input["userId"] = $userId;
        $input["username"] = $inputData["username"];
        $resultVal = EditProfileTransaction::valUsername($input);
        return response()->json([
            'status' => 'OK',
            'data' => $resultVal
        ]);
    }

    public function editProfile(Request $request){
        $inputData  = $request->all();
        $userId = System::userLoginId();

        $input = [
            "userId" => $userId,
            "username" => $inputData["username"],
            "fullName" => $inputData["fullName"]
        ];
        $resultVal = EditProfileTransaction::valUsername($input);
        if($resultVal>0) {
            return response()->json([
                'status' => 'FAIL',
                'error' => "Username isn't available. Please try another."
            ]);
        } else {

            $insertLog = [
                "userId" => $userId,
                "refId" => -99,
                "message" => 'You has successfully update your profile',
                "type" => 'EDIT_PROFILE',
                "intRemark" => ''
            ];

            RecentLogActivityTransaction::generateLogActivity($insertLog);

            EditProfileTransaction::editProfile($input);
            return response()->json([
                'status' => 'OK'
            ]);
        }

    }

}
