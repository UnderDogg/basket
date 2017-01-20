<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DefaultEmailTemplateChanges
 *
 * @author EA
 */
class DefaultEmailTemplateChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $template = \App\Basket\Template::first();
        $template->html = file_get_contents('doc/resources/default_email_template.blade.php');
        $template->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
