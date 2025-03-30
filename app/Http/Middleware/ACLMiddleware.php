<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ACLMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = Route::currentRouteName();
        $user = auth()->user()->load(['roles' => fn($roles) => ['permissions' => $roles->with('permissions')]]);

        if (!$user->hasAnyPermissions($user, $routeName)) {
            abort(403, 'Not authorized');
        }

        return $next($request);
    }
}
