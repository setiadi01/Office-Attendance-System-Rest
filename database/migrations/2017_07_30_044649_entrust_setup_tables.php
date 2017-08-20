<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('t_role', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->integer('version')->default(0);
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('t_role_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->string('flg_default', 1);
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->integer('version')->default(0);

            $table->foreign('user_id')->references('user_id')->on('t_user')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('t_role')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('t_permission', function (Blueprint $table) {
            $table->bigIncrements('permission_id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->integer('version')->default(0);
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('t_permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->integer('version')->default(0);

            $table->foreign('permission_id')->references('permission_id')->on('t_permission')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('t_role')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('t_permission_role');
        Schema::drop('t_permission');
        Schema::drop('t_role_user');
        Schema::drop('t_role');
    }
}
