<?php

use Illuminate\Database\Seeder;

use App\System\System;
/**
 * @author  Setiadi, 20 Agustus 2017
 */

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('m_employee')->delete();
        DB::table('m_employee')->insert([
        	[
				'person_id' => 1,
        		'job_id' => 1,
        		'user_id' => 1,
        		'join_date' => '20170913',
        		'start_date' => '20170913',
				'last_date' => '',
				'placement' => 'semarang',
				'membership' => 'semarang',
				'supervisor_id' => 1,
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	],
        	[
				'person_id' => 2,
				'job_id' => 1,
				'user_id' => 2,
				'join_date' => '20170913',
				'start_date' => '20170913',
				'last_date' => '',
				'placement' => 'semarang',
				'membership' => 'semarang',
				'supervisor_id' => 1,
				'create_datetime' => System::dateTime(),
				'update_datetime' => System::dateTime(),
				'create_user_id' => -99,
				'update_user_id' => -99
        	]
        ]);
    }
}
