<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction\ReasonTransaction;
use App\Transaction\ManageLostCheckInTransaction;
use App\System\System;
use App\Transaction\DailyAuthenticationTransaction;
use App\Transaction\AttendanceTransaction;
use App\Transaction\RecentLogActivityTransaction;
use Exception;
use Illuminate\Support\Facades\DB;

class ManageLostCheckinController extends Controller
{
    public function changeReason(Request $request){
        DB::beginTransaction();
        try {
            $input = $request->all();
            $reasonCode = $input['reasonCode'];
            if($reasonCode == 'C') {
                return $this->cuti($input);
            } else if($reasonCode == 'DD') {
                return $this->dinasDalamKota($input);
            } else if($reasonCode == 'DL') {
                return $this->dinasLuarKota($input);
            } else if($reasonCode == 'L') {
                return $this->lupaCheckin($input);
            }

        } catch (Exception $e) {
            \Log::debug($e);
            DB::rollBack();
            return response()->json([
                'status' => 'FAIL',
                'result' => $e->getCode()==0?$e->getMessage():"Internal Eror : ".$e->getCode()
            ]);
        }

    }

    private function cuti($param){
        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];

        $inputGenerateManageCheckin = [
            'checkinDate' => $checkinDate,
            'reasonCode' => 'C'
        ];
        return $this->generateManageCheckin($inputGenerateManageCheckin);
    }

    private function dinasLuarKota($param){
        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];

        $inputGenerateManageCheckin = [
            'checkinDate' => $checkinDate,
            'reasonCode' => 'DL'
        ];
        return $this->generateManageCheckin($inputGenerateManageCheckin);
    }

    private function dinasDalamKota($param){
        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];

        $inputGenerateManageCheckin = [
            'checkinDate' => $checkinDate,
            'reasonCode' => 'DD'
        ];
        return $this->generateManageCheckin($inputGenerateManageCheckin);
    }

    private function lupaCheckin($param){
        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];

        $inputGenerateManageCheckin = [
            'checkinDate' => $checkinDate,
            'reasonCode' => 'L'
        ];
        return $this->generateManageCheckin($inputGenerateManageCheckin);
    }

    private function generateManageCheckin($param) {

        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];
        $reasonCode = $param['reasonCode'];
        $reasonId = -99;

        $inputFindReason = ['reasonCode'=> $reasonCode];
        $outputReason = ReasonTransaction::findByIndex($inputFindReason);

        if($outputReason==null) {
            throw new Exception("Invalid reason");
        } else {
            $reasonId = $outputReason->reason_id;
        }

        $inputFindDailyAuth = [
            'userId'=> $userId,
            'generatedDate'=> $checkinDate
        ];
        $outputDailyAuth = DailyAuthenticationTransaction::findByIndex($inputFindDailyAuth);
        if($outputDailyAuth!=null && $outputDailyAuth->auth_date_checkin !='') {
            throw new Exception("You already have checkin");
        }

        $inputFindManageLostCheckin = [
            'userId'=> $userId,
            'checkinDate'=> $checkinDate
        ];
        $outputManageLostChekin = ManageLostCheckInTransaction::findByIndex($inputFindManageLostCheckin);

        if($outputManageLostChekin!=null) {
            throw new Exception("Reason already exists");

        } else {
            $inputAddManageLostCheckin = [
                'checkinDate' => $checkinDate,
                'reasonId' => $reasonId
            ];
            $output = ManageLostCheckInTransaction::add($inputAddManageLostCheckin);

            if($output==null) {
                throw new Exception("Error to add manage lost chekin");
            } else if($reasonCode == 'L'){
                $inputGenerateCheckin = [
                    'checkinDate' => $checkinDate,
                    'reason' => $outputReason
                ];

                return $this->generateCheckin($inputGenerateCheckin);

            } else {

                $insertLog = [
                    "userId" => $userId,
                    "refId" => -99,
                    "message" => 'You has successfully to change reason lost check in',
                    "type" => 'LOSTCHECKIN',
                    "intRemark" => $outputReason->description
                ];
                RecentLogActivityTransaction::generateLogActivity($insertLog);

                DB::commit();
                return response()->json([
                    'status' => 'OK',
                    'result' => $output
                ]);
            }
        }


    }

    private function generateCheckin($param) {
        $userId = System::userLoginId();
        $checkinDate = $param['checkinDate'];
        $reason = $param['reason'];
        $dailyAuthenticationId = -99;

        $inputFindDailyAuth = [
            'userId'=> $userId,
            'generatedDate'=> $checkinDate
        ];
        $outputDailyAuth = DailyAuthenticationTransaction::findByIndex($inputFindDailyAuth);
        if($outputDailyAuth!=null) {
            if ($outputDailyAuth->auth_date_checkin != '') {
                throw new Exception("You already have checkin");
            } else {
                $dailyAuthenticationId = $outputDailyAuth->daily_authentication_id;
                $inputUpdate = [
                    'id' => $dailyAuthenticationId,
                    'authKeyCheckin' => 'GENERATEBYLATEREASON',
                    'authDateCheckin' => $checkinDate,
                    'authKeyCheckout' => 'GENERATEBYLATEREASON',
                    'authDateCheckout' => $checkinDate
                ];
                DailyAuthenticationTransaction::update($inputUpdate);
            }
        } else {

            $inputDailyAuth = [
                'generatedDate' => $checkinDate,
                'authKeyCheckin' => 'GENERATEBYLATEREASON',
                'authDateCheckin' => $checkinDate,
                'authKeyCheckout' => 'GENERATEBYLATEREASON',
                'authDateCheckout' => $checkinDate

            ];

            $outputDailyAuth = DailyAuthenticationTransaction::add($inputDailyAuth);

            if($outputDailyAuth==null) {
                throw new Exception("Error to generate daily auth");
            } else {
                $dailyAuthenticationId = $outputDailyAuth->daily_authentication_id;
            }
        }

        if($dailyAuthenticationId!=-99) {
            $inputForAddAttendance = [
                'dailyAuthenticationId'=> $dailyAuthenticationId,
                'checkinDatetime'=> $checkinDate."083000",
                'checkoutDatetime'=> $checkinDate."180000",
                'status' => 'O'
            ];
            $outputAddAttendance = AttendanceTransaction::add($inputForAddAttendance);

            if($outputAddAttendance==null) {
                throw new Exception("Error to add attendance");
            } else {

                $insertLog = [
                    "userId" => $userId,
                    "refId" => -99,
                    "message" => 'You has successfully to change reason lost check in',
                    "type" => 'LOSTCHECKIN',
                    "intRemark" => $reason->description
                ];
                RecentLogActivityTransaction::generateLogActivity($insertLog);

                DB::commit();
                return response()->json([
                    'status' => 'OK',
                    'result' => $outputAddAttendance
                ]);
            }

        } else {
            throw new Exception("Daily auth id not found");
        }
    }

}