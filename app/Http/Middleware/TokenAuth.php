<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class TokenAuth
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
        $cookie = $request->cookie("token", null);
        if($cookie == null){
			return redirect()->route('login');
		}
		
		// Get user by token
		try
		{
			$user = JWTAuth::parseToken()->authenticate();
			if (!$user) {
				if($request->isJson()){
					return response()->json(['Error' => 'User not found'], 404);
				}

				return redirect()->route('login');
			}

		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			if($request->isJson()){
				return response()->json(['Error' => 'Token is Expired', 'ErrorCode' => 1], 403);
			}

			return redirect()->route('login');

		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			if($request->isJson()){
				return \response()->json(['Error' => 'Token is Invalid', 'ErrorCode' => 2], 403);
			}

			return redirect()->route('login');

		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			if($request->isJson()){
				return response()->json(['Error' => 'Token is Absent', 'ErrorCode' => 3], 403);
			}

			return redirect()->route('login');
		}

		return $next($request);
    }
}
