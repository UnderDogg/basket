<?php

namespace App\Http\Middleware;

class Messages
{
    public function handle($request, $next)
    {
        view()->share('messages', is_array(session()->get('messages'))?session()->get('messages'):[]);
        return $next($request);
    }
}