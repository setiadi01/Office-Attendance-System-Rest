<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMPersonProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_person_profile', function (Blueprint $table) {
            $table->increments('person_profile_id');
            $table->string('prefix_title');
            $table->string('name');
            $table->string('sufix_title');
            $table->string('pob');
            $table->string('dob');
            $table->string('religin');
            $table->string('marital_status');
            $table->string('sex');
            $table->string('blood_type');
            $table->string('email_address');
            $table->string('mobile_no');
            $table->string('current_address');
            $table->string('city');
            $table->string('province');
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->integer('version')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_person_profile');
    }
}
