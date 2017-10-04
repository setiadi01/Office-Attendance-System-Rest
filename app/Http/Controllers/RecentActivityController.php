<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System\System;
use App\Transaction\RecentLogActivityTransaction;

/**
 * @author  Cong, 27 September 2017
 */

class RecentActivityController extends Controller
{

    public function getRecentActivity(Request $request){

        $userId = System::userLoginId();
        $inputData = $request->all();
        $limit = $inputData['limit'];
        $offset = $inputData['offset'];

        $input =[
            'userId' => $userId,
            'limit' => $limit,
            'offset' => $offset
        ];

        $getRecentActivityList = RecentLogActivityTransaction::getRecentActivityList($input);
        return response()->json([
            'status' => 'OK',
            'recentActivityList' => $getRecentActivityList['recentActivityList']
        ]);
    }

}
