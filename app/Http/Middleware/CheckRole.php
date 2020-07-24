<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $arRole = explode("|", $role);
        
        foreach ($arRole as $role)
		{
			if ($request->user()->isRole($role)) {
				return $next($request);
			}
		}

		return response()->json(['Error' => 'Access denied'], 403);
    }
}
