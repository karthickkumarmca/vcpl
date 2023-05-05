<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Usersession
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
        $rolesAccess = Session::get('role_access');
        if(empty($rolesAccess)){
             return route('login');
        }
        if(!isset($rolesAccess['dashboard'])){
             return route('login');
        }
    return $next($request);
    }
}
