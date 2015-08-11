<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Closure;

/**
 * Download
 *
 * @author WN
 * @package App\Http\Middleware
 */
class Download
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->get('download') && array_key_exists('api_data', $response->original->getData())) {

            return response()->json($response->original->getData()['api_data']);
        }

        return $response;
    }
}
