<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author EB
 * Class DepositLimitForProduct
 */
class DepositLimitForProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('installation_id')->unsigned()->references('id')->on('installations');
            $table->string('product');
            $table->integer('min_deposit_percentage')->unsigned();
            $table->integer('max_deposit_percentage')->unsigned();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_limits');
    }
}
