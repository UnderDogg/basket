<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds email templates
 *
 * @author EB
 */
class EmailTemplates extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('templates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('merchant_id')->nullable()->unsigned();
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->boolean('active')->default(true);
            $table->text('html');
            $table->timestamps();
        });

        Schema::create('installation_template', function(Blueprint $table)
        {
            $table->integer('template_id')->unsigned();
            $table->integer('installation_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('installation_id')->references('id')->on('installations');
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('templates', function(Blueprint $table)
        {
            $table->dropForeign('templates_merchant_id_foreign');
        });

        Schema::table('installation_template', function(Blueprint $table)
        {
           $table->dropForeign('installation_template_template_id_foreign');
           $table->dropForeign('installation_template_installation_id_foreign');
        });

        Schema::drop('templates');
        Schema::drop('installation_template');
    }
}
