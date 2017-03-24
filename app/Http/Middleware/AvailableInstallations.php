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

use App\Basket\Installation;
use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Available Installations
 *
 * @author WN
 * @package App\Http\Middleware
 */
class AvailableInstallations
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $installations = Installation::where('active', true);

        if ($this->auth->user() && $this->auth->user()->merchant_id) {
            view()->share(
                'available_installations',
                $installations->where('merchant_id', $this->auth->user()->merchant_id)->get()
            );
        } else {
            view()->share('available_installations', $installations->get());
        }

        return $next($request);
    }
}
