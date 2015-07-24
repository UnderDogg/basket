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

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->string('ext_company_name');
            $table->string('ext_address');
            $table->string('ext_processing_days');
            $table->string('ext_minimum_amount_settled');
            $table->string('ext_address_on_agreements');
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->string('name');
            $table->boolean('active');
            $table->boolean('linked');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->string('ext_id');
            $table->string('ext_name');
            $table->string('ext_return_url');
            $table->string('ext_notification_url');
            $table->string('ext_default_product');

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
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('installation_id')->references('id')->on('installations');
        });

        Schema::table('users', function (Blueprint $table) {

            $table->integer('merchant_id')->unsigned()->after('password')->default(null)->nullable(); // Added defaults: fix for sqlite
            $table->string('locations')->after('merchant_id')->default(''); // Added defaults: fix for sqlite
            $table->integer('role_id')->unsigned()->default(null)->nullable();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('installation_id')->unsigned();
            $table->integer('location_id')->unsigned()->nullable();
            $table->integer('ext_id');
            $table->string('ext_current_status');
            $table->string('ext_order_reference');
            $table->integer('ext_order_amount');
            $table->string('ext_order_description')->nullable();
            $table->string('ext_order_validity')->nullable();
            $table->string('ext_products_groups');
            $table->json('ext_products_options')->nullable();
            $table->string('ext_products_default');
            $table->string('ext_fulfilment_method');
            $table->string('ext_fulfilment_location');
            $table->string('ext_customer_title')->nullable();
            $table->string('ext_customer_first_name')->nullable();
            $table->string('ext_customer_last_name')->nullable();
            $table->string('ext_customer_email_address')->nullable();
            $table->string('ext_customer_phone_home')->nullable();
            $table->string('ext_customer_phone_mobile')->nullable();
            $table->string('ext_customer_postcode')->nullable();
            $table->string('ext_application_address_abode')->nullable();
            $table->string('ext_application_address_building_name')->nullable();
            $table->string('ext_application_address_building_number')->nullable();
            $table->string('ext_application_address_street');
            $table->string('ext_application_address_locality')->nullable();
            $table->string('ext_application_address_town')->nullable();
            $table->string('ext_application_address_postcode');
            $table->string('ext_applicant_title')->nullable();
            $table->string('ext_applicant_first_name')->nullable();
            $table->string('ext_applicant_last_name')->nullable();
            $table->string('ext_applicant_date_of_birth')->nullable();
            $table->string('ext_applicant_email_address')->nullable();
            $table->string('ext_applicant_phone_home')->nullable();
            $table->string('ext_applicant_phone_mobile')->nullable();
            $table->string('ext_applicant_postcode')->nullable();
            $table->integer('ext_finance_order_amount');
            $table->integer('ext_finance_loan_amount');
            $table->integer('ext_finance_deposit');
            $table->integer('ext_finance_subsidy');
            $table->integer('ext_finance_net_settlement');
            $table->json('ext_metadata')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('last_sync_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('installation_id')->references('id')->on('installations');
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
        Schema::drop('merchants');
        Schema::drop('installations');
        Schema::drop('locations');

        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('merchant_id');
            $table->dropColumn('locations');
        });
    }
}
