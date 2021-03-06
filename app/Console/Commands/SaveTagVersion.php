<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

/**
 * Class SaveTagVersion
 *
 * @author CS
 * @package App\Console\Commands
 */
class SaveTagVersion extends Command
{
    /** @var String */
    protected $signature = 'tag:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save the current tag to version.json in the app\'s root directory.';

    /** @var Filesystem $filesystem */
    protected $filesystem;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $tag = $this->filesystem->get(base_path() . '/VERSION.md');

        $includeFile = base_path('resources/views/includes/page') . '/version.blade.php';
        $time = date('Y-m-d H:i:s');

        $content = '{{--GENERATED: ' . $time . '--}}' . PHP_EOL . 'version: ' . $tag . PHP_EOL;

        try {
            $this->filesystem->put($includeFile, $content);
        } catch (\Exception $e) {
            Log::error('Error while trying to create blade version file: ' . $e->getMessage());
        }
    }
}
