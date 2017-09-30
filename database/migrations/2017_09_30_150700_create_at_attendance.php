<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtAttendancecard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('at_attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->bigInteger('user_id');
            $table->bigInteger('daily_authentication_id');
            $table->string('checkin_datetime',14);
            $table->string('checkout_datetime',14);
            $table->string('status',1);
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
        Schema::dropIfExists('m_person_idcard');
    }
}
