<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds Application Events
 *
 * @author SL
 */
class ApplicationEvents extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('application_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->references('id')->on('applications');
            $table->integer('user_id')->unsigned()->nullable()->references('id')->on('users');
            $table->integer('type');
            $table->string('description');
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
        Schema::drop('application_events');
    }
}
