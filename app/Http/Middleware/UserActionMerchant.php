<?php

namespace App\Http\Middleware;

use Closure;

class UserActionMerchant
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
        $user = \Auth::getUser();

        if (!(empty($user->merchant_id) ||
            $user->merchant_id == $request->id
        )) {
            return redirect('/')->with('error', 'You are not allowed to take an action on this Merchant');
        }

        return $next($request);
    }
}
