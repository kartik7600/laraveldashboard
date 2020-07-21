<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class ManagerMiddleware
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
        switch ( Auth::user()->user_role ) {
            case 'admin':
                return redirect::route('a.dashboard');
                break;

            case 'account_manager':
                return $next($request);
                break;

            default:
                return redirect::route('client.dashboard');
                break;
        }
    }
}
