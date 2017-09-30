<?php

namespace App\Http\Controllers\Auth;

use App\Transaction\GenerateQrCodeTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\System\System;
use App\Transaction\ApiAuthTransaction;
use App\Transaction\EditProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use Response;
use Log;


/**
 * Digunakan untuk Autentikasi API
 * @author  Setiadi, 20 Agustus 2017
 */

class ApiAuthController extends Controller
{

    /**
     * Digunakan untuk absen API, ketika sukses akan mengembalikan response
     * yang berisi token dan data user yang sedang absen
     * @param Request $request 
     * @return Json
     */
    public function login(Request $request){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $role = System::defaultRole($user->user_id);
            $profilePicture = ApiAuthTransaction::getProfilePicture($user->username);
            $personProfile = EditProfileTransaction::getPersonProfile($user->user_id);
            $user->role = $role;
            $user->profile_picture = $profilePicture;

            // mengembalikan data yang diperlukan saja
            $data['username'] = $user->username;
            $data['full_name'] = $user->full_name;
            $data['email'] = $user->email;
            $data['role'] = $user->role;
            $data['profile_picture'] = $user->profile_picture==null?'':$user->profile_picture;
            $data["phone_number"] = $personProfile->mobile_no;

            $token =  $user->createToken('Absensi')->accessToken;
            return response()->json([
                'status' => 'OK', 
                'token' => $token,
                'user' => $data
            ]);
        }
        else{
            return response()->json([
                'status' => 'FAIL', 
                'error' => 'Unauthenticated'
            ]);
        }
    }

    /**
     * Digunakan untuk absen API, ketika sukses akan mengembalikan response
     * yang berisi token dan data user yang sedang absen
     * @param Request $request 
     * @return Json
     */
    public function loginWeb(Request $request){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $role = System::defaultRole($user->user_id);
            $profilePicture = ApiAuthTransaction::getProfilePicture($user->username);
            $addAuthLogin = ApiAuthTransaction::addAuthLogin();
            $user->role = $role;
            $user->profile_picture = $profilePicture;
            $token =  $user->createToken('Absensi')->accessToken;
           
            $userId = Session::get('user');
            \Log::debug($userId);

            return response()->json([
                'status' => 'OK',
                'token' => $token,
                'user' => $user
            ]);
        }
        else{
            return response()->json([
                'status' => 'FAIL',
                'error' => 'Unauthenticated'
            ]);
        }
    }

    /**
     * Mendapatkan profile picture
     * @param type $username 
     * @return String
     */
    public function getProfilePicture($username){
        $pictureName = ApiAuthTransaction::getProfilePicture($username);
        if ($pictureName == null) {
            return response()->json([
                'data' => null
            ]);    
        }else{        
            return response()->json([
                'data' => $pictureName
            ]);      
        }
    }

}