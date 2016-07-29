<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class EmailTemplateConfigurationDefaults
 *
 * @author EB
 */
class EmailTemplateConfigurationDefaults extends Migration
{
    private $field = 'email_configuration';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new AddEmailConfigurationToInstallation)->down();

        Schema::table('installations', function (Blueprint $table) {
            $table->json($this->field);
        });

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
        Schema::table('installations', function (Blueprint $table) {
            $table->dropColumn($this->field);
        });

        (new AddEmailConfigurationToInstallation)->up();
    }
}
