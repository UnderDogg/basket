<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'push/*',
    ];

    /**
     * Doesn't handle the VerifyCsrfToken Middleware when testing the application
     *
     * @author EB
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ('testing' !== app()->environment())
        {
            return parent::handle($request, $next);
        }

        return $next($request);
    }
}
