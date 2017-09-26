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
				'checkin_datetime' => System::date(),
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
			->update([	'checkout_datetime' 	=> $now,
						'status' => 'O',
						'update_datetime' 	=> System::dateTime(),
						'version' => DB::raw('version + 1') ]);
	}


//	public static function getUuid(){
//		$userId = System::userLoginId();
//		$getUuid = DB::SELECT("
//			SELECT uuid
//			FROM t_daily_authentication_seq
//			WHERE user_id = $userId;
//		");
//
//		return $getUuid[0]->uuid;
//	}


}