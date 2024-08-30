<?php

namespace App\Http\Middleware;

use Closure;

class RqlgAuthAPI
{
    public $attributes;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $user = \App\User::where('token_api', $request->header('Authorization'));
        $user = \App\User::where('api_token', $request->header('Authorization'))->first();
        $request->attributes->add(['user_id' => $user->id]);

        $currentAppVersion = config('app.version');
        $appVersion = $request->header('AppVersion');
        if ($user) {
            \App\User::setUserLogged($user);

            if ($currentAppVersion != $appVersion) {
                return abort(426);
            }
        }

        $response = $next($request);
        //$response->header('AppVersion', $currentAppVersion);

        return $response;
    }
}
