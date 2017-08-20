<?php

use Illuminate\Database\Seeder;

use App\System\System;
/**
 * @author  Setiadi, 20 Agustus 2017
 */

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('t_user')->delete();
        DB::table('t_user')->insert([
        	[
        		'user_id' => 1,
        		'full_name' => 'Supervisor',
        		'username' => 'supervisor',
        		'email' => 'supervisor@gmail.com',
        		'password' => bcrypt('123456'),
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	],
        	[
        		'user_id' => 2,
        		'full_name' => 'User',
        		'username' => 'user',
        		'email' => 'user@gmail.com',
        		'password' => bcrypt('123456'),
        		'create_datetime' => System::dateTime(),
        		'update_datetime' => System::dateTime(),
        		'create_user_id' => -99,
        		'update_user_id' => -99
        	]
        ]);
    }
}
