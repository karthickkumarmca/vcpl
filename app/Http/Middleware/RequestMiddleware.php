<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Logger;
use Illuminate\Support\Facades\Log;

class RequestMiddleware
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
        $token = $request->header('Authorization');
        $data=array(
            'token'=>$token,
            'method'=>$request->method(),
            'url'=>$request->url(),
            'requestparams'=>$request->all()
        );
        $log = new Log();
        Log::channel('request')->notice(json_encode($data));
        return $next($request);
    }
}