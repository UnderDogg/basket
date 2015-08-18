<?php

namespace App\Http\Middleware;

/**
 * Class Messages
 *
 * @author EB
 * @package App\Http\Middleware
 */
class Messages
{
    /**
     * @author EB
     * @param $request
     * @param $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        view()->share('messages', is_array(session()->get('messages'))?session()->get('messages'):[]);
        return $next($request);
    }
}
