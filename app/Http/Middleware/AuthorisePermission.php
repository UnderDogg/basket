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

use Auth;
use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorisePermission
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     * @throws HttpException
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::user()->can($permission)) {
            return $next($request);
        }

        throw new HttpException(403, 'No valid permissions');
    }
}
