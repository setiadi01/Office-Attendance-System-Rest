<?php

use Illuminate\Database\Seeder;

use App\System\System;
/**
 * @author  Setiadi, 20 Agustus 2017
 */

class EmployeeAdditionalInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('m_employee_additional_info')->delete();
        DB::table('m_employee_additional_info')->insert([
        	[
				'employee_id' => 1,
        		'profile_picture' => 'foto1.jpg',
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	],
        	[
				'employee_id' => 2,
				'profile_picture' => 'foto2.jpg',
				'create_datetime' => System::dateTime(),
				'update_datetime' => System::dateTime(),
				'create_user_id' => -99,
				'update_user_id' => -99
        	]
        ]);
    }
}
