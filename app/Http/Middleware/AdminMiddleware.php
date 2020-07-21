<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class AdminMiddleware
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
        /*if( Auth::user()->user_role == 'admin' )
        {
            return $next($request);
        }
        
        return redirect('/');
        */
        
        switch ( Auth::user()->user_role ) {
            case 'admin':
                return $next($request);
                break;

            case 'account_manager':
                return redirect::route('manager.dashboard');
                break;

            default:
                return redirect::route('client.dashboard');
                break;
        }

    }
}
