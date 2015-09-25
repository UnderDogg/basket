<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds a default of true to the active field on locations
 *
 * @author EB
 * Class AddDefaultToActiveOnLocations
 */
class AddDefaultToActiveOnLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function ($table) {
            $table->boolean('active')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
