<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class RemoveAssistedJourneyColumn
 *
 * @author GK
 */
class RemoveAssistedJourneyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @author GK
     * @return void
     */
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->dropColumn('assisted_journey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @author GK
     * @return void
     */
    public function down()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->boolean('assisted_journey')->default(false)->after('merchant_payments');
        });
    }
}
