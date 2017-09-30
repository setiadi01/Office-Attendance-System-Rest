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
		$now = System::date();
		DB::table('t_daily_authentication')
					->where('user_id', $userId)
					->where('generated_date', $now)
					->update([	'auth_key_checkout' 	=> $checkout,
						'auth_date_checkout' => System::date(),
						'update_datetime' 	=> System::dateTime(),
						'version' => DB::raw('version + 1') ]);

		$id = DB::table('t_daily_authentication')
			->select('daily_authentication_id')
			->where('user_id', $userId)
			->where('generated_date', $now)
			->first();

		DB::table('at_attendance')
			->where('user_id', $userId)
			->where('checkin_datetime', $now)
			->update([	'checkout_datetime' 	=> System::dateTime(),
						'status' => 'O',
						'update_datetime' 	=> System::dateTime(),
						'version' => DB::raw('version + 1') ]);
	}

	public static function getReportList($input){
		$startDate = $input['startDate'];
		$endDate = $input['endDate'];
		$userId = $input['userId'];
		$limit = $input['limit'];
		$offset = $input['offset'];

		$list  = DB::select("SELECT B.full_name, to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'FMDay') checkin_day, 
                                to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'DD') checkin_date, 
                                to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'FMMonth') checkin_month, 
                                to_char(to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'), 'HH24.MI') checkin_hours, 
                                to_char(to_timestamp(A.checkout_datetime, 'YYYYMMDDHH24MISS'), 'HH24.MI') checkout_hours
                                FROM at_attendance A
                                INNER JOIN t_user B ON A.user_id = B.user_id
                                WHERE A.user_id = $userId 
                                AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
                                limit $limit offset $offset
                            ");

		return [
			"reportList" => $list
		];


	}

	public static function getSummaryReport($input){
		$startDate = $input['startDate'];
		$endDate = $input['endDate'];
		$userId = $input['userId'];

		$notCheckIn  = DB::select("SELECT count(*) AS not_checkin FROM dt_date A
                                    WHERE NOT EXISTS 
                                        (SELECT 1 FROM at_attendance B 
                                          WHERE A.string_date = SUBSTRING(B.checkin_datetime,1,8) 
                                          AND B.user_id=$userId
                                        )
                                    AND A.string_date between '$startDate' AND '$endDate'
						        ");

		$checkIn  = DB::select("SELECT count(*) AS checkin FROM at_attendance A
                                  INNER JOIN t_daily_authentication B ON A.daily_authentication_id = B.daily_authentication_id
                                  WHERE B.user_id = $userId 
                                  AND B.auth_date_checkin BETWEEN '$startDate' AND '$endDate'
                              ");

		$lateToCheckIn  = DB::select("SELECT count(*) AS late_to_checkin  FROM at_attendance A
                                        WHERE A.user_id = $userId 
                                        AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
                                        AND SUBSTRING(A.checkin_datetime,9,4) > '0820'
								    ");

		$workingHours  = DB::select("SELECT COALESCE(SUM(EXTRACT(HOUR FROM to_timestamp(A.checkout_datetime, 'YYYYMMDDHH24MISS') - to_timestamp(A.checkin_datetime, 'YYYYMMDDHH24MISS'))), 0) AS working_hours		
                                        FROM at_attendance A
                                        WHERE A.user_id = $userId
                                        AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate'
							        ");

		$bestCheckIn = DB::select("SELECT to_char(to_timestamp(MIN(SUBSTRING(A.checkin_datetime,9,4)), 'HH24MI'), 'HH24:MI') AS best_checkin
									FROM at_attendance A
									WHERE A.user_id = $userId
									AND SUBSTRING(A.checkin_datetime,1,8) BETWEEN '$startDate' AND '$endDate' 
								  ");

		return [
			"checkIn" => $checkIn[0]->checkin,
			"notCheckIn" => $notCheckIn[0]->not_checkin,
			"lateToCheckIn" => $lateToCheckIn[0]->late_to_checkin,
			"workingHours" => $workingHours[0]->working_hours,
			"bestCheckIn" => $bestCheckIn[0]->best_checkin==''?'-':$bestCheckIn[0]->best_checkin
		];

	}

}