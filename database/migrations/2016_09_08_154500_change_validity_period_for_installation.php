<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author EB
 * Class ChangeValidityPeriodForInstallation
 */
class ChangeValidityPeriodForInstallation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installations', function (Blueprint $table) {
            $table->integer('validity')->default(2592000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('installations', function (Blueprint $table) {
            $table->integer('validity')->default(7200)->change();
        });
    }
}
