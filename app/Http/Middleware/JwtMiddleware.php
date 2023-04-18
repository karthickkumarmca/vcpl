<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Models\User;

class JwtMiddleware extends BaseMiddleware
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
        try {
            $token = $request->header('Authorization');
            $user = JWTAuth::setToken($token)->authenticate();
            $userID = isset($user->id) ? $user->id : '';
            if($userID=='') {
                $message = 'Token was expired';
                $data = ['message' => $message,'data'=> (Object)[],'error'=>(Object)[]];
                return response()->json($data, 401)->setEncodingOptions(JSON_NUMERIC_CHECK);
            }
            
            // if($dataValue->status == 2 || $dataValue->status ==0) {

            //     $message  = 'Your Account Temporarily not accessed Please contact Admin';
            //     $data = ['message' => $message,'data'=> (Object)[],'error'=>(Object)[]];
            //     return response()->json($data, 401)->setEncodingOptions(JSON_NUMERIC_CHECK);
            // }

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $message = 'Token is Invalid';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $message = 'Token is Expired';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException){
                $message = 'Token is Invalid';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                $message = 'Token black listed';
            }else{
                $message  = 'Authorization Token not found';
            }

            $data = ['message' => $message,'data'=> (Object)[],'error'=>(Object)[]];
            return response()->json($data, 401)->setEncodingOptions(JSON_NUMERIC_CHECK);
        }
        $request->auth = $user;
        return $next($request);
    }
}