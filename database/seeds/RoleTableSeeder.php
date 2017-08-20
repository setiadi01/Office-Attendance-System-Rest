<?php

use Illuminate\Database\Seeder;

use App\System\System;

/**
 * @author  Setiadi, 20 Agustus 2017
 */

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('t_role')->delete();
        DB::table('t_role')->insert([
        	[
        		'role_id' => 1,
        		'name' => 'supervisor',
        		'display_name' => 'Supervisor',
        		'description' => 'Supervisor',
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	],
        	[
        		'role_id' => 2,
        		'name' => 'employee',
        		'display_name' => 'Karyawan',
        		'description' => 'Karyawan',
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	]
        ]);
    }
}
