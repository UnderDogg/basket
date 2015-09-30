<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Add finance product group to table
 *
 * @author SL
 * Class AddActiveProductGroupToApplicationsTable
 */
class AddActiveProductGroupToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ext_chosen_finance_product_group')->nullable()->after('ext_order_validity');
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
            $table->dropColumn('ext_chosen_finance_product_group');
        });
    }
}
