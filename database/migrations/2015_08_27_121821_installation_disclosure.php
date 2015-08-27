<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Installation Disclosure Migration
 *
 * @author WN
 */
class InstallationDisclosure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->string('custom_logo_url')->nullable()->after('validity');
            $table->text('disclosure')->nullable()->after('custom_logo_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->dropColumn('custom_logo_url');
            $table->dropColumn('disclosure');
        });
    }
}
