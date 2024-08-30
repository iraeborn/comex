<?php

namespace App\Http\Middleware;

use Closure;

class RqlgAuth
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
        if (!\Session::get('user_id')) return redirect('/');

        if($user = \App\User::find(\Session::get('user_id'))){
            \App\User::setUserLogged($user);
        }
        
        
        $response = $next($request);
        $response->header('AppVersion', config('app.env'));
        
        return $response;
    }
}
