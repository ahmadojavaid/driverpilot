<?php

namespace App\Http\Middleware;

use Closure;

class IsInstructorMiddleware
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
        if(auth()->user()->isInstructor()) {
            return $next($request);
        }
        return response()->json(['message' => 'Sorry you are not authorize'], 401);
    }
}
