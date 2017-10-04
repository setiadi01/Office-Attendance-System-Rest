<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\GenerateQrCodeTransaction;
use App\Transaction\ApiAuthTransaction;
use App\Transaction\SystemTransaction;
use App\Transaction\RecentLogActivityTransaction;
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
        \Log::debug("checkin!");

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

                $insertLog = [
                    "userId" => $userId,
                    "refId" => -99,
                    "message" => 'You has successfully to check in',
                    "type" => 'CHECKIN',
                    "intRemark" => ''
                ];

                RecentLogActivityTransaction::generateLogActivity($insertLog);

				$proccesCheckin = ApiAuthTransaction::checkin($userId, $checkin);
				return response()->json([
					'status' => 'OK'
				]);
			} else {
				return response()->json([
					'status' => 'FAIL',
					'error' => 'Failed to verify check in, please try using valid QrCode'
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'FAIL',
				'error' => 'Failed to verify check in, please try using valid QrCode'
			]);
		}

	}

	public function checkout(Request $request){
        \Log::debug("checkout!");
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
                $insertLog = [
                    "userId" => $userId,
                    "refId" => -99,
                    "message" => 'You has successfully to check out',
                    "type" => 'CHECKOUT',
                    "intRemark" => ''
                ];

                RecentLogActivityTransaction::generateLogActivity($insertLog);

				$proccesCheckout = ApiAuthTransaction::checkout($userId, $checkout);
				return response()->json([
					'status' => 'OK'
				]);
			} else {
				return response()->json([
					'status' => 'FAIL',
					'error' => 'Failed to verify check in, please try using valid QrCode'
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

		return response()->json([
			'status' => 'OK',
			'reportList' => $getReportList['reportList'],
			'onTime' => $getSummeryReport['onTime'],
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

    public function getSummaryChart(){
        $userId = System::userLoginId();

        $input =[
            'userId' => $userId
        ];


        $getSummeryReport = ApiAuthTransaction::getSumaryForChartData($input);

        return response()->json([
            'status' => 'OK',
            'summaryChartData' => $getSummeryReport['summaryChartData']
        ]);

    }

}
