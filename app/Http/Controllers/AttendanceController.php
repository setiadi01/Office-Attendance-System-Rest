<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\GenerateQrCodeTransaction;
use Illuminate\Support\Facades\Auth;

/**
 * @author  Setiadi, 20 Agustus 2017
 */

class AttendanceController extends Controller
{
	/**
	 * Mengembalikan data user yang login
	 * @return Json
	 */
	public function checkin(Request $request){
		$inputData  = $request->all();
		$userId = System::userLoginId();
		$username = System::userUsername();

		$getUuid = GenerateQrCodeTransaction::getUuid($userId);
		if($getUuid != null) {
			$now = System::dateTimeForQrCode();
			$qrcode = $username . '_' . $now . '_' . $getUuid;
			Log::debug($qrcode);
			$resultQrCode = md5(utf8_encode($qrcode));
			$checkin = $inputData['checkin'];

			if ($resultQrCode == $checkin) {
				$proccesCheckin = ApiAuthTransaction::checkin($username, $userId);
				return response()->json([
					'status' => 'OK'
				]);
			} else {
				return response()->json([
					'status' => 'FAIL',
					'error' => 'QrCode Not Equals'
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'FAIL',
				'error' => 'UUID Not Found'
			]);
		}

	}

	public function checkout(Request $request){
		$inputData  = $request->all();
		$userId = System::userLoginId();
		$username = System::userUsername();

		$getUuid = GenerateQrCodeTransaction::getUuid($userId);
		if($getUuid != null) {
			$now = System::dateTimeForQrCode();
			$qrcode = $username . '_' . $now . '_' . $getUuid;
			$resultQrCode = md5(utf8_encode($qrcode));
			$checkout = $inputData['checkout'];

			if ($resultQrCode == $checkout) {
				$proccesCheckout = ApiAuthTransaction::checkout($username, $userId);
				return response()->json([
					'status' => 'OK'
				]);
			} else {
				return response()->json([
					'status' => 'FAIL',
					'error' => 'QrCode Not Equals'
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'FAIL',
				'error' => 'UUID Not Found'
			]);
		}
	}
}
