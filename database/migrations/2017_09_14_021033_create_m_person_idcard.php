<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMPersonIdcard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_person_idcard', function (Blueprint $table) {
            $table->increments('person_idcard_id');
            $table->string('card_type');
            $table->string('card_no');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('exp_date');
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
