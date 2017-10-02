<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\GenerateQrCodeTransaction;
use App\Transaction\ApiAuthTransaction;
use App\Transaction\SystemTransaction;
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
			$qrcode = $username.'_'.$now.'_'.$getUuid;
			$resultQrCode = md5($qrcode);
			$checkin = $inputData['checkin'];

			if ($resultQrCode == $checkin) {
				$proccesCheckin = ApiAuthTransaction::checkin($userId, $checkin);
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
			$resultQrCode = md5($qrcode);
			$checkout = $inputData['checkout'];
			if ($resultQrCode == $checkout) {
				$proccesCheckout = ApiAuthTransaction::checkout($userId, $checkout);
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

	public function getReportAbsen(Request $request){
		$inputData  = $request->all();

		$startDate = $inputData['startDate'];
		$endDate = $inputData['endDate'];
        $username  = $inputData['username'];
		$limit  = $inputData['limit'];
		$offset  = $inputData['offset'];

        $userId = SystemTransaction::getUserIdByUsername($username);

		$input =[
			'startDate' => $startDate,
			'endDate' => $endDate,
			'userId' => $userId,
			'limit' => $limit,
			'offset' => $offset
		];

		$getReportList = ApiAuthTransaction::getReportList($input);
		$getSummeryReport = ApiAuthTransaction::getSummaryReport($input);

        \Log::debug($input);

		return response()->json([
			'status' => 'OK',
			'reportList' => $getReportList['reportList'],
			'notCheckIn' => $getSummeryReport['notCheckIn'],
			'checkIn' => $getSummeryReport['checkIn'],
			'lateToCheckIn' => $getSummeryReport['lateToCheckIn'],
			'workingHours' => $getSummeryReport['workingHours']
		]);

	}

	public function getSummaryWeekly(){
        $userId = System::userLoginId();
		$dateNow = System::date();
		$startDate = SystemTransaction::getThisWeekMondayDate();

		$input =[
			'startDate' => $startDate,
			'endDate' => $dateNow,
            'userId' => $userId
		];


		$getSummeryReport = ApiAuthTransaction::getSummaryReport($input);

		return response()->json([
			'status' => 'OK',
			'lateToCheckIn' => $getSummeryReport['lateToCheckIn'],
			'workingHours' => $getSummeryReport['workingHours'],
			'bestCheckIn' => $getSummeryReport['bestCheckIn']
		]);

	}

}
