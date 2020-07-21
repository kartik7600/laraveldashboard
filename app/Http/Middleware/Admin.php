<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
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
        if ( ! Auth::check()) {
            if ($request->ajax()) {
                return response('Unauthorized, please login.', 401);
            } else {
                return redirect()->route('a.login');
            }
        }

        return $next($request);
    }
}
