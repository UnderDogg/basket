<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class MakeLocationInstructionNullable
 *
 * @author GK
 */
class MakeLocationInstructionNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @author SL
     * @return void
     */
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->text('location_instruction')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @author SL
     * @return void
     */
    public function down()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->text('location_instruction')->nullable(false)->change();
        });
    }
}
