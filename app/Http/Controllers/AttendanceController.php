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

        $inputData = $request->all();
        $userId = SystemTransaction::getUserIdByUsername($inputData['username']);
        $username = $inputData['username'];
        $getUuid = GenerateQrCodeTransaction::getUuid($userId);

        if(SystemTransaction::getStatusAbsen($userId)!='N') {
            return response()->json([
                'status' => 'FAIL',
                'error' => 'You have checked out for today, please do check in again for tomorrow'
            ]);
        }

        if ($getUuid != null) {
            $now = System::dateTimeForQrCode();
            $qrcode = $username . '_' . $now . '_' . $getUuid;
            $resultQrCode = md5($qrcode);
            $checkin = $inputData['checkin'];

            \Log::debug($checkin." = ".$resultQrCode);

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
        } else {
            return response()->json([
                'status' => 'FAIL',
                'error' => 'Failed to verify check in, please try using valid QrCode'
            ]);
        }
	}

	public function checkout(Request $request){
        \Log::debug("checkout!");
		$inputData  = $request->all();
        $userId = SystemTransaction::getUserIdByUsername($inputData['username']);
        $username = $inputData['username'];
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
				'error' => 'Failed to verify check in, please try using valid QrCode'
			]);
		}
	}

	public function getReportAbsen(Request $request){
		$inputData  = $request->all();

		$startDate = $inputData['startDate'];
		$endDate = $inputData['endDate'];
        $username  = $inputData['username'];
        $type  = isset($inputData['type'])?$inputData['type']:'';
		$limit  = $inputData['limit'];
		$offset  = $inputData['offset'];

		\Log::debug($type);

        $userId = SystemTransaction::getUserIdByUsername($username);

		$input =[
			'startDate' => $startDate,
			'endDate' => $endDate,
            'userId' => $userId,
            'type' => $type,
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
            'notCheckIn' => $getSummeryReport['notCheckIn'],
			'lateToCheckIn' => $getSummeryReport['lateToCheckIn'],
			'workingHours' => $getSummeryReport['workingHours']
		]);

	}

	public function getLastInfoCheckin(){
        $userId = System::userLoginId();
        $endDate = System::date();
		$startDate = SystemTransaction::getThisWeekMondayDate();

        $dayNow = System::dayNow();
        $monthNow = System::monthNow();
        $yearNow = System::yearNow();
		if($dayNow < 21 ) {
            $monthNow = ($monthNow-1) == 0 ? 12:$monthNow-1;
            if($monthNow == 12) $yearNow = $yearNow-1;
        }
        $dayNow = 21;
        $startDate = $yearNow.sprintf("%02d", $monthNow).sprintf("%02d",$dayNow);

        $dayNow = System::dayNow();
        $monthNow = System::monthNow();
        $yearNow = System::yearNow();
        if($dayNow > 20 ) {
            $monthNow = ($monthNow+1) == 13 ? 1:$monthNow+1;
            if($monthNow == 1) $yearNow = $yearNow+1;
        }
        $dayNow = 21;
        $endDate = $yearNow.sprintf("%02d", $monthNow).sprintf("%02d",$dayNow);

		$input =[
			'startDate' => $startDate,
			'endDate' => $endDate,
            'userId' => $userId
		];


		$getSummeryReport = ApiAuthTransaction::getSummaryReport($input);
		$getLastInfoCheckin = ApiAuthTransaction::getLastInfoCheckin($input);


		return response()->json([
			'status' => 'OK',
			'bestCheckIn' => $getSummeryReport['bestCheckIn'],
			'lastCheckIn' => $getLastInfoCheckin['lastCheckin'],
            'workDay' => $getLastInfoCheckin['workDay'],
			'workHour' => $getLastInfoCheckin['workHour'],
			'workMinute' => $getLastInfoCheckin['workMinute'],
			'workSec' => $getLastInfoCheckin['workSec'],
			'statusAbsen' => SystemTransaction::getStatusAbsen($userId)

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

    public function getReasonList() {
        $getReasonList = ApiAuthTransaction::getReasonList();

        return response()->json([
            'status' => 'OK',
            'reasonList' => $getReasonList['reason_list']
        ]);
    }

    public function checkUpdate(Request $request) {
        if($request['version']!=System::currentVersion()) {
            return response()->json([
                'status' => 'REQUIRED_UPDATE',
                'message' => 'Whats new :<br />A. Improvement :<br />- Perubahan jam masuk dari 08.20 menjadi 08.30 . Lebih dari 08.30 tercatat sebagai telat<br />- Perubahan default filter Report, menjadi 21 mon year - 20 mon year<br />- Perubahan informasi chart, menjadi 3 bulan sebelumnya sampai bulan sekarang<br />- Beberapa perbaikan di web absen<br /><br />B. Bug Fix :<br />- Bug fix tidak bisa checkin dihari selanjutnya<br />- Bug fix error data, untuk checkout dilain hari<br />- Bug fix foto profil tidak update saat ganti user<br />- Bug fix informasi role'
            ]);
        }
    }

}
