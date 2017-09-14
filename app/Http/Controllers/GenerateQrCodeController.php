<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Transaction\GenerateQrCodeTransaction;
use Session;
use App\System\System;
use Log;


/**
 * @author  Setiadi, 20 Agustus 2017
 */

class GenerateQrCodeController extends Controller
{
	/**
	 * Mengembalikan data user yang login
	 * @return Json
	 */
    public function getQrCode(){
		$userId = Session::get('user');

		//$userId = System::userLoginId();
		Log::debug($userId);
//		$getUuid = GenerateQrCodeTransaction::getUuid($userId);
//		$now = System::dateTime();
//		$qrcode = $userId.'_'.$now.'_'.$getUuid;
//		return response()->json([
//    		'status' => 'OK',
//    		'data' => $qrcode
//    	]);
    }
}
