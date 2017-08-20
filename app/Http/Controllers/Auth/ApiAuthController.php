<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\System\System;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Log;

/**
 * Digunakan untuk Autentikasi API
 * @author  Setiadi, 20 Agustus 2017
 */

class ApiAuthController extends Controller
{

    /**
     * Digunakan untuk login API, ketika sukses akan mengembalikan response
     * yang berisi token dan data user yang sedang login
     * @param Request $request 
     * @return Json
     */
    public function login(Request $request){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $role = System::defaultRole($user->user_id);
            $user->role = $role;
            $token =  $user->createToken('Absensi')->accessToken;

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
}