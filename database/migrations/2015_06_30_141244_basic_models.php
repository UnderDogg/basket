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
            $table->boolean('linked')->default(false);
            $table->string('ext_company_name');
            $table->string('ext_address');
            $table->string('ext_processing_days');
            $table->string('ext_minimum_amount_settled');
            $table->string('ext_address_on_agreements');
            $table->timestamps();
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->string('name');
            $table->boolean('active');
            $table->boolean('linked');
            $table->string('ext_id');
            $table->string('ext_name');
            $table->string('ext_return_url');
            $table->string('ext_notification_url');
            $table->string('ext_default_product');
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

        Schema::table('users', function (Blueprint $table) {

            $table->integer('merchant_id')->unsigned()->after('password');
            $table->string('locations')->after('merchant_id');

            $table->foreign('merchant_id')->references('id')->on('merchants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchants');
        Schema::drop('installations');
        Schema::drop('locations');

        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('merchant_id');
            $table->dropColumn('locations');
        });
    }
}
