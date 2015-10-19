<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Cancellation Concept
 *
 * @author WN
 */
class ApplicationCancellation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('ext_cancellation_fee_amount')->nullable()->after('ext_finance_net_settlement');
            $table->string('ext_cancellation_description')->nullable()->after('ext_finance_net_settlement');
            $table->dateTimeTz('ext_cancellation_requested_date')->nullable()->after('ext_finance_net_settlement');
            $table->dateTimeTz('ext_cancellation_effective_date')->nullable()->after('ext_finance_net_settlement');
            $table->boolean('ext_cancellation_requested')->nullable()->after('ext_finance_net_settlement');
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
            $table->dropColumn('ext_cancellation_requested');
            $table->dropColumn('ext_cancellation_effective_date');
            $table->dropColumn('ext_cancellation_requested_date');
            $table->dropColumn('ext_cancellation_description');
            $table->dropColumn('ext_cancellation_fee_amount');
        });
    }
}
