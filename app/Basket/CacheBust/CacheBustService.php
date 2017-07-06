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
    public static function getVersionHash()
    {
        $file = '../' . config('basket.cacheBustHashSource');

        if (!file_exists($file)) {
            \Log::error('Frontend Cache Buster: expected CB Hash Source file does not exist. [' . $file . ']');

            return md5(uniqid());
        }

        return md5(filemtime($file));
    }

    /**
     * Static Accessor for use in View
     *
     * @param string $filePath
     * @return string
     * @author SL
     */
    public static function cache($filePath)
    {
        return $filePath . '?v=' . self::getVersionHash();
    }
}
