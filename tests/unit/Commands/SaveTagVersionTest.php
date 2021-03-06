<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\User;
use App\Http\Controllers;

class SaveTagVersionTest extends BrowserKitTestCase
{
    /**
     * @author CS
     */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('tag:save');
    }

    /**
     * @author CS
     */
    public function testFailure()
    {
        $versionFile = base_path() . '/resources/views/includes/page/version.blade.php';
        $this->assertFileExists($versionFile);
    }
}
