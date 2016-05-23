<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author EB
 * Class AddFinanceOffersToInstallation
 */
class AddFinanceOffersToInstallation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->integer('finance_offers')->default(2);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('ext_resume_url')->nullable()->after('ext_metadata');
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
            $table->dropColumn('finance_offers');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('ext_resume_url');
        });
    }
}
