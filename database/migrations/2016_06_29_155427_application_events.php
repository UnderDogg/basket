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
        Schema::create('application_events', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('application_id')->references('id')->on('applications');
            $table->integer('type');
            $table->string('description');
            $table->json('metadata');
            $table->timestamps();
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
