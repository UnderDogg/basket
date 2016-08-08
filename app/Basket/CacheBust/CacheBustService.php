<?php

namespace App\Basket\CacheBust;

/**
 * Frontend Cache Busting Service.
 *
 * Class CacheBustService
 * @package PayBreak\Basket\CacheBust
 * @author SL
 */
class CacheBustService
{
    /**
     * Get a hash based on the last modified time of the application's configured source file.
     * This should be a file touched on every deployment.
     *
     * @return string
     * @author SL
     */
    public function getVersionHash()
    {
        $file = '../' . env('CACHE_BUST_HASH_SOURCE', 'VERSION.md');

        // We need to fail gracefully.
        // A failure here means we'll not allow users browsers to cache any resources *but* crucially
        // pages will still load.
        if (!file_exists($file)) {

            \Log::error('Frontend Cache Buster: expected CB Hash Source file does not exist. [' . $file . ']');

            return md5(uniqid());
        }

        return md5(filemtime($file));
    }
}
