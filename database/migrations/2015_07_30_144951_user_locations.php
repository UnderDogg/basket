<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('locations');
        });

        Schema::create('location_user', function (Blueprint $table) {
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('location_user');

        Schema::table('users', function (Blueprint $table) {
            $table->string('locations')->after('merchant_id')->default('');
        });
    }
}
