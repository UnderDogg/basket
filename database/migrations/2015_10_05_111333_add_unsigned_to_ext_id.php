<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnsignedToExtId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function ($table) {
            $table->integer('ext_id')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // NOTE: This has been disabled with the DB engine upgrades as
        // setting the column to signed causes data violations. Code left for clarity in future.
        //
        // Schema::table('applications', function ($table) {
        //     $table->integer('ext_id')->signed()->change();
        // });
    }
}
