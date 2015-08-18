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
            $table->string('token', 100)->unique();
            $table->boolean('active')->default(false);
            $table->boolean('linked')->default(false);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->string('ext_company_name')->nullable();
            $table->string('ext_address')->nullable();
            $table->string('ext_processing_days')->nullable();
            $table->string('ext_minimum_amount_settled')->nullable();
            $table->string('ext_address_on_agreements')->nullable();
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->string('name');
            $table->boolean('active')->default(false);
            $table->boolean('linked')->default(false);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->string('ext_id')->nullable()->unique();
            $table->string('ext_name')->nullable();
            $table->string('ext_return_url')->nullable();
            $table->string('ext_notification_url')->nullable();
            $table->string('ext_default_product')->nullable();
            $table->text('location_instruction');

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
            $table->integer('ext_id')->nullable()->unique();
            $table->string('ext_current_status')->nullable();
            $table->string('ext_order_reference')->nullable();
            $table->integer('ext_order_amount')->nullable();
            $table->string('ext_order_description')->nullable();
            $table->string('ext_order_validity')->nullable();
            $table->string('ext_products_groups')->nullable();
            $table->json('ext_products_options')->nullable();
            $table->string('ext_products_default')->nullable();
            $table->string('ext_fulfilment_method')->nullable();
            $table->string('ext_fulfilment_location')->nullable();
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
            $table->string('ext_application_address_street')->nullable();
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
            $table->integer('ext_finance_order_amount')->nullable();
            $table->integer('ext_finance_loan_amount')->nullable();
            $table->integer('ext_finance_deposit')->nullable();
            $table->integer('ext_finance_subsidy')->nullable();
            $table->integer('ext_finance_net_settlement')->nullable();
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
        Schema::drop('applications');

        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign('users_merchant_id_foreign');
            $table->dropForeign('users_role_id_foreign');

            $table->dropColumn('role_id');
            $table->dropColumn('locations');
            $table->dropColumn('merchant_id');

        });

        Schema::drop('locations');
        Schema::drop('installations');
        Schema::drop('merchants');
    }
}
