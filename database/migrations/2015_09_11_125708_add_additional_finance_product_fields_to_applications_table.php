<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Application: Finance additional fields migration.
 *
 * @author SL
 */
class AddAdditionalFinanceProductFieldsToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ext_finance_option')->nullable()->after('ext_applicant_postcode');
            $table->integer('ext_finance_holiday')->nullable()->after('ext_finance_option');
            $table->integer('ext_finance_payments')->nullable()->after('ext_finance_holiday');
            $table->integer('ext_finance_term')->nullable()->after('ext_finance_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('ext_finance_option');
            $table->dropColumn('ext_finance_holiday');
            $table->dropColumn('ext_finance_payments');
            $table->dropColumn('ext_finance_term');
        });
    }
}
