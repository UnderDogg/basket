<?php

use App\Basket\Location;
use App\Helpers\NotificationPreferences;
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
            $table->renameColumn('converted_email', 'notifications');
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
            if ($location->notifications->has(NotificationPreferences::CONVERTED)) {
                $location->notifications = [NotificationPreferences::CONVERTED];
            } else {
                $location->notifications = [];
            }

            $location->save();
        }

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('notifications', 'converted_email');
        });
    }
}
