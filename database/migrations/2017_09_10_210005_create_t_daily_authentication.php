<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDailyAuthentication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_daily_authentication', function (Blueprint $table) {
            $table->increments('daily_authentication_id');
            $table->bigInteger('user_id');
            $table->string('secure_key', 100);
            $table->string('generated_date', 8);
            $table->string('auth_key_checkin', 100);
            $table->string('auth_date_checkin', 8);
            $table->string('auth_key_checkout', 100);
            $table->string('auth_date_checkout', 8);
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
        Schema::dropIfExists('t_daily_authentication');
    }
}
