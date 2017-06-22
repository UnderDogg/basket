<?php

use App\Basket\Location;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmailFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @author GK
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('converted_email', 'email_settings');
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
        $locations = Location::all();
        foreach ($locations as $location) {
            /** @var App\Basket\Location location */
            $location->email_settings = $location->getConvertedEmailSetting() ? 1 : 0;

            $location->save();
        }

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('email_settings', 'converted_email');
        });
    }
}
