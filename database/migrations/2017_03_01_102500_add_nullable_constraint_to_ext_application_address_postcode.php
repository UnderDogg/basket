<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Add Nullable Constraint To Ext Application Address Postcode
 *
 * @author EB
 */
class AddNullableConstraintToExtApplicationAddressPostcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('ext_application_address_postcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to rollback changes as `up()` was not nullable initially
    }
}
