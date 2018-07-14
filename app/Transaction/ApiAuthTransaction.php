<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\System\System;

/**
* @author Setiadi, 10 September 2017
*/

class ApiAuthTransaction
{
	
	public static function getProfilePicture($username){
		$result = DB::table('t_user as a')
			->join('m_employee as b', 'b.user_id', '=', 'a.user_id')
			->join('m_employee_additional_info as c', 'c.employee_id', '=', 'b.employee_id')
			->select('c.profile_picture')
			->where('a.username', $username)
			->first();

		return $result == null ? null : $result->profile_picture;
	}

	public static function getUserDaily(){
		$userId = System::userLoginId();
		$result = DB::table('t_daily_authentication')
			->where([
				['user_id', '=', $userId],
				['generated_date', '=', System::date()],
			])
			->first();

		return $result == null ? null : $result;
	}

	public static function addAuthLogin(){
		$userId = System::userLoginId();
		$now = System::date();
		$uuid = Uuid::uuid1();
		$getUserDaily = ApiAuthTransaction::getUserDaily();
		if ($getUserDaily == null) {
			DB::table('t_daily_authentication')->insert([
				[
					'user_id' => $userId,
					'secure_key' => $uuid,
					'generated_date' => System::date(),
					'auth_key_checkin' => '',
					'auth_date_checkin' => '',
					'auth_key_checkout' => '',
					'auth_date_checkout' => '',
					'version' => 0,
					'create_datetime' => System::dateTime(),
					'update_datetime' => System::dateTime(),
					'create_user_id' => $userId,
					'update_user_id' => $userId
				]]);
		}
		else
		{
			DB::table('t_daily_authentication')
				->where('user_id', $userId)
				->where('generated_date', $now)
				->update(['secure_key' => $uuid]);
		}

		return null;
	}

	public static function checkin($userId, $checkin){
		$now = System::date();
		DB::table('t_daily_authentication')
					->where('user_id', $userId)
					->where('generated_date', $now)
					->update([	'auth_key_checkin' 	=> $checkin,
								'auth_date_checkin' => System::date(),
								'update_datetime' 	=> System::dateTime(),
								'version' => DB::raw('version + 1')]);


		$result = DB::table('t_daily_authentication')
			->select('daily_authentication_id')
			->where('user_id', $userId)
			->where('generated_date', $now)
			->first();

		$id = $result->daily_authentication_id;

		DB::table('at_attendance')->insert([
				'user_id' => $userId,
				'daily_authentication_id' => $id,
				'checkin_datetime' => System::dateTime(),
				'checkout_datetime' => '',
				'status' => 'I',
				'version' => 0,
				'create_datetime' => System::dateTime(),
				'update_datetime' => System::dateTime(),
				'create_user_id' => $userId,
				'update_user_id' => $userId
		]);

	}

	public static function checkout($userId, $checkout){
		DB::table('t_daily_authentication')
					->where('user_id', $userId)
                    ->where('auth_key_checkin', '!=', '')
					->where('auth_key_checkout', '')
					->update([	'auth_key_checkout' 	=> $checkout,
						'auth_date_checkout' => System::date(),
						'update_datetime' 	=> System::dateTime(),
						'version' => DB::raw('version + 1') ]);

		DB::table('at_attendance')
			->where('user_id', $userId)
			->where('checkout_datetime', '')
			->update([	'checkout_datetime' 	=> System::dateTime(),
						'status' => 'O',
						'update_datetime' 	=> System::dateTime(),
						'version' => DB::raw('version + 1') ]);
	}

	public static function getReportList($input){
		$startDate = $input['startDate'];
		$endDate = $input['endDate'];
        $userId = $input['userId'];
        $type = $input['type'];
		$limit = $input['limit'];
		$offset = $input['offset'];
        $dateNow = System::date();
        $list = [];

		$filterCheckinHour = '';
		if($type!=null && $type!='') {
		    if($type == 'LATE') {
                $filterCheckinHour = " AND SUBSTRING(A.checkin_datetime,9,4) > '0830' ";
            } else if($type == 'ONTIME') {
                $filterCheckinHour = " AND SUBSTRING(A.checkin_datetime,9,4) <= '0830' ";
            }
        }

        if($type!=null && $type!='' && $type=='NOTCHECKIN') {
            $list  = DB::select("
                                WITH raw_data AS (
                                    SELECT A.user_id, B.string_date, A.full_name, 
                                        to_char(B.string_date::date, 'FMDay') checkin_day, 
                                        to_char(B.string_date::date, 'DD') checkin_date, 
                                        to_char(B.string_date::date, 'FMMonth') checkin_month, 
                                        'x' checkin_hours, 
                                        'x' checkout_hours, 'N' AS check_status
                                    FROM t_user A, dt_date B
                                    WHERE B.string_date BETWEEN '$startDate' AND '$endDate'
                                    AND A.user_id = $userId
                                    AND B.string_date <= '$dateNow'
                                    AND B.flg_holiday = 'N'
                                    AND NOT EXISTS (SELECT 1 FROM at_attendance AX 
                                        WHERE B.string_date = SUBSTRING(AX.checkin_datetime,1,8) 
                                        AND AX.checkin_datetime !='' 
	                                    AND AX.user_id = A.user_id
                                        )
                                    ORDER BY B.string_date DESC
                                ) 
                                SELECT A.string_date, A.full_name, A.checkin_day, A.checkin_date, 
                                    A.checkin_month, A.checkin_hours, A.checkout_hours, 
                                    A.check_status, COALESCE(C.reason_code, '') AS reason_code, 
                                    COALESCE(C.reason_name, '') AS reason_name, COALESCE(C.description, '') AS description
                                FROM raw_data A
                                LEFT JOIN t_manage_lost_checkin B ON A.user_id = B.user_id AND A.string_date = B.checkin_date
                                LEFT JOIN t_reason C ON B.reason_id = C.reason_id
                                ORDER BY A.string_date DESC
                                LIMIT $limit offset $offset
                            ");
        } else {
            $list = DB::select("SELECT B.full_name, to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'FMDay') checkin_day, 
                                    to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'DD') checkin_date, 
                                    to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'FMMonth') checkin_month, 
                                    to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'HH24.MI') checkin_hours, 
                                    CASE WHEN A.checkout_datetime <> '-' 
                                      THEN to_char(to_timestamp(A.checkout_datetime, 'YYYYMMDDHH24MISS'), 'HH24.MI') 
                                      ELSE '-'
                                    END AS checkout_hours, 
                                    'Y' AS check_status, '' AS reason_code, '' AS reason_name, '' AS description
                                    FROM at_attendance A
                                    INNER JOIN t_user B ON A.user_id = B.user_id
                                    WHERE A.user_id = $userId 
                                    AND A.checkin_datetime !=''
                                    AND A.checkout_datetime !=''
                                    " . $filterCheckinHour .
                "
                                    AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
                                    ORDER BY A.checkin_datetime DESC  
                                    limit $limit offset $offset
                                ");

        }

		return [
			"reportList" => $list
		];


	}

	public static function getSummaryReport($input){
		$startDate = $input['startDate'];
		$endDate = $input['endDate'];
		$userId = $input['userId'];
		$dateNow = System::date();

        $onTime  = DB::select("SELECT count(*) AS on_time  FROM at_attendance A
                                        WHERE A.user_id = $userId 
                                        AND A.checkin_datetime !=''
                                        AND A.checkout_datetime !=''
                                        AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
                                        AND SUBSTRING(A.checkin_datetime,9,4) <= '0830'
								    ");

		$checkIn  = DB::select("SELECT count(*) AS checkin FROM at_attendance A
                                  INNER JOIN t_daily_authentication B ON A.daily_authentication_id = B.daily_authentication_id
                                  WHERE B.user_id = $userId 
                                  AND A.checkin_datetime !=''
                                  AND A.checkout_datetime !=''
                                  AND B.auth_date_checkin BETWEEN '$startDate' AND '$endDate'
                              ");

		$lateToCheckIn  = DB::select("SELECT count(*) AS late_to_checkin  FROM at_attendance A
                                        WHERE A.user_id = $userId 
                                        AND A.checkin_datetime !=''
                                        AND A.checkout_datetime !=''
                                        AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
                                        AND SUBSTRING(A.checkin_datetime,9,4) > '0830'
								    ");

        $workingHours  = DB::select("SELECT COALESCE(SUM(EXTRACT(HOUR FROM to_timestamp(A.checkout_datetime, 'YYYYMMDDHH24MISS') - to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'))), 0) AS working_hours		
                                        FROM at_attendance A
                                        WHERE A.user_id = $userId
                                        AND A.checkin_datetime !=''
                                        AND A.checkout_datetime !=''
                                        AND A.checkout_datetime !='-'
                                        AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
							        ");

		$bestCheckIn = DB::select("SELECT to_char(to_timestamp(MIN(SUBSTRING(A.checkin_datetime,9,4)), 'HH24MI'), 'HH24:MI') AS best_checkin
									FROM at_attendance A
									WHERE A.user_id = $userId
									AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate' 
								  ");

        $notCheckIn  = DB::select("SELECT COUNT(1) AS not_check_in FROM dt_date A
                                    WHERE A.flg_holiday = 'N'
                                    AND A.string_date BETWEEN '$startDate' AND '$endDate'
                                    AND A.string_date <= '$dateNow'
                                    AND NOT EXISTS (SELECT 1 
                                            FROM at_attendance Z
                                            INNER JOIN t_daily_authentication Y ON Z.daily_authentication_id = Y.daily_authentication_id
                                            WHERE A.string_date = Y.auth_date_checkin
                                            AND Z.user_id = $userId
                                    
                                    )");

		return [
			"checkIn" => $checkIn[0]->checkin,
            "onTime" => $onTime[0]->on_time,
			"lateToCheckIn" => $lateToCheckIn[0]->late_to_checkin,
			"workingHours" => $workingHours[0]->working_hours,
			"bestCheckIn" => $bestCheckIn[0]->best_checkin==''?'-':$bestCheckIn[0]->best_checkin,
            "notCheckIn" => $notCheckIn[0]->not_check_in
		];

	}

	public static function getLastInfoCheckin($input){
		$userId = $input['userId'];
		$checkIn  = DB::select("WITH get_last_checkin AS (
									SELECT MAX(A.checkin_datetime) AS checkin_datetime, A.user_id FROM at_attendance A
									WHERE A.user_id = $userId
									GROUP BY A.user_id
								)
								SELECT  to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'HH24MISS') AS last_checkin,
                                EXTRACT(DAY FROM to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS')-current_timestamp)::int*-1 AS work_day, 
								EXTRACT(HOUR FROM to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS')-current_timestamp)::int*-1 AS work_hour,
								EXTRACT(MINUTE FROM to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS')-current_timestamp)::int*-1 AS work_minute,
								EXTRACT(SECOND FROM to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS')-current_timestamp)::int*-1 AS work_sec 
								FROM at_attendance A
								INNER JOIN get_last_checkin B ON A.user_id = B.user_id AND A.checkin_datetime = B.checkin_datetime
                              ");
		return [
			"lastCheckin" => $checkIn[0]->last_checkin,
            "workDay" => $checkIn[0]->work_day,
            "workHour" => $checkIn[0]->work_hour,
			"workMinute" => $checkIn[0]->work_minute,
			"workSec" => $checkIn[0]->work_sec
		];
	}

    public static function getSumaryForChartData($input){
        $userId = $input['userId'];
        $dateNow = System::date();

        $list  = DB::select("
                            WITH dt_date_last_3_month AS (
                                SELECT A.user_id, B.string_date, B.year_month_date AS tahun_bulan, B.flg_holiday  
                                FROM t_user A, dt_date B
                                WHERE B.year_month_date BETWEEN to_char(current_date - '2 month'::interval, 'YYYYMM') AND to_char(current_date, 'YYYYMM')
                                AND A.user_id = $userId
                                AND B.string_date <= '$dateNow'
                            ), last_3_month AS (
                                SELECT user_id, tahun_bulan 
                                FROM dt_date_last_3_month
                                GROUP BY user_id, tahun_bulan 
                            ), on_time_check_in AS (
                                SELECT A.user_id, count(1) AS on_time, B.tahun_bulan  
                                FROM at_attendance A
                                INNER JOIN dt_date_last_3_month B ON A.user_id = B.user_id AND SUBSTRING(A.checkin_datetime,1,8) = B.string_date
                                WHERE A.checkin_datetime !=''
                                AND A.checkout_datetime !=''
                                AND SUBSTRING(A.checkin_datetime,9,4) <= '0830'
                                GROUP BY A.user_id, B.tahun_bulan  
                            ), late_check_in AS (
                                SELECT A.user_id, count(1) AS late, B.tahun_bulan  
                                FROM at_attendance A
                                INNER JOIN dt_date_last_3_month B ON A.user_id = B.user_id AND SUBSTRING(A.checkin_datetime,1,8) = B.string_date
                                WHERE A.checkin_datetime !=''
                                AND A.checkout_datetime !=''
                                AND SUBSTRING(A.checkin_datetime,9,4) > '0830'
                                GROUP BY A.user_id, B.tahun_bulan 
                            ), not_checkin AS (
                                SELECT A.user_id, count(1) AS not_checkin, A.tahun_bulan  
                                FROM dt_date_last_3_month A
                                WHERE A.flg_holiday = 'N'
                                AND NOT EXISTS (SELECT 1 FROM at_attendance AX 
                                    WHERE A.string_date = SUBSTRING(AX.checkin_datetime,1,8) 
                                    AND AX.checkin_datetime !='' 
                                )
                                GROUP BY A.user_id, A.tahun_bulan
                            )
                            SELECT COALESCE(B.on_time, 0) AS on_time, COALESCE(C.late, 0) AS late, COALESCE(D.not_checkin, 0) AS not_checkin, 
                                COALESCE(B.on_time,0)+COALESCE(C.late, 0) AS total_check_in, TO_CHAR((A.tahun_bulan||'01')::date, 'Month') AS bulan
                            FROM last_3_month A
                            LEFT JOIN on_time_check_in B ON A.user_id = B.user_id AND A.tahun_bulan = B.tahun_bulan
                            LEFT JOIN late_check_in C ON A.user_id = C.user_id AND A.tahun_bulan = C.tahun_bulan
                            LEFT JOIN not_checkin D ON A.user_id = D.user_id AND A.tahun_bulan = D.tahun_bulan
                            ORDER BY A.tahun_bulan ASC
                            ");

        return [
            "summaryChartData" => $list
        ];


    }

    public static function getReasonList(){
        $list  = DB::select("SELECT reason_code, reason_name, description FROM t_reason A WHERE A.active = 'Y' ORDER BY reason_name");

        return [
            "reason_list" => $list
        ];


    }

}
