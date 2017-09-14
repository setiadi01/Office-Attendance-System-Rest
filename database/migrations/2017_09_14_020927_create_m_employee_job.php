<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMEmployeeJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_employee_job', function (Blueprint $table) {
            $table->increments('employee_job_id');
            $table->bigInteger('employee_id');
            $table->string('start_date');
            $table->bigInteger('job_id');
            $table->string('placement');
            $table->string('membership');
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
        Schema::dropIfExists('m_employee_job');
    }
}
