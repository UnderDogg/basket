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
        Schema::table('installations', function (Blueprint $table) {
            $table->integer('validity')->default(2592000)->change();
        });

        $installations = \App\Basket\Installation::all();

        /** @var \App\Basket\Installation $installation */
        foreach ($installations as $installation) {
            if ($installation->validity < 86400) {
                $installation->validity = 86400;
                $installation->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->integer('validity')->default(7200)->change();
        });
    }
}
