<?php

use Illuminate\Database\Seeder;

use App\System\System;
/**
 * @author  Setiadi, 20 Agustus 2017
 */

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('t_role_user')->delete();
        DB::table('t_role_user')->insert([
        	[
        		'user_id' => 1,
        		'role_id' => 1,
        		'flg_default' => 'Y',
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	],
        	[
        		'user_id' => 2,
        		'role_id' => 2,
        		'flg_default' => 'Y',
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	]
        ]);
    }
}
