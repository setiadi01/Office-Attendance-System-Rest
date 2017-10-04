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
    public function getQrCode($username, $userId){
		$getUuid = GenerateQrCodeTransaction::getUuid($userId);
		if($getUuid != null){
			$now = System::dateTimeForQrCode();
			$qrcode = $username.'_'.$now.'_'.$getUuid;
			$result = md5($qrcode);
			return response()->json([
				'status' => 'OK',
				'data' => $result
			]);
		}
		else{
			return response()->json([
				'status' => 'FAIL'
			]);
		}

    }
}
