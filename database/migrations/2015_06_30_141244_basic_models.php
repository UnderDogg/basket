<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BasicModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('token', 100);
            $table->timestamps();
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference');
            $table->integer('merchant_id')->unsigned();
            $table->string('name');
            $table->boolean('active');
            $table->boolean('linked');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference');
            $table->integer('installation_id')->unsigned();
            $table->boolean('active');
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->timestamps();

            $table->foreign('installation_id')->references('id')->on('installations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('installations');
        Schema::drop('locations');
    }
}
