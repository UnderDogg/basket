<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Add Ext Feature Merchant Liable To Installations Table
 *
 * @author EB
 */
class AddExtFeatureMerchantLiableToInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->boolean('ext_feature_merchant_liable')->after('ext_default_product')->default(false);
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
            $table->dropColumn('ext_feature_merchant_liable');
        });
    }
}
