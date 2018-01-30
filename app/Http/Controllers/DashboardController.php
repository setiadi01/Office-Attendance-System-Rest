<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\ReportWebTransaction;

class DashboardController extends Controller
{

    public function getDetailCheckinList(Request $request){

        if(System::roleAdmin()!= System::defaultRole()) {
            return response()->json([
                'status' => 'FAIL',
                'response' => 'unauthorized'
            ]);
        }

        $input =[
            'startDate' => $request['start_date'],
            'endDate' => $request['end_date'],
            'limit' => $request['limit'],
            'offset' => $request['offset']
        ];
        $outputDetailCheckinList = ReportWebTransaction::getDetailCheckinList($input);
        return response()->json([
            'status' => 'OK',
            'response' => $outputDetailCheckinList['detailCheckinList']
        ]);
    }

    public function getSummaryCheckinList(Request $request){

        if(System::roleAdmin()!= System::defaultRole()) {
            return response()->json([
                'status' => 'FAIL',
                'response' => 'unauthorized'
            ]);
        }

        $input =[
            'startDate' => $request['start_date'],
            'endDate' => $request['end_date'],
            'limit' => $request['limit'],
            'offset' => $request['offset']
        ];
        $outputSummaryCheckinList = ReportWebTransaction::getSummaryCheckinList($input);
        return response()->json([
            'status' => 'OK',
            'response' => $outputSummaryCheckinList['summaryCheckinList']
        ]);
    }

}
