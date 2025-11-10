<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users with parent role
        if (auth()->check() && auth()->user()->hasRole('parent')) {
            $user = auth()->user();
            
            // If onboarding is not complete and not already on onboarding page
            if (!$user->onboarding_complete && !$request->routeIs('onboarding') && !$request->is('api/parent-onboarding/*')) {
                return redirect()->route('onboarding');
            }
            
            // If onboarding is complete and on onboarding page, redirect to dashboard
            if ($user->onboarding_complete && $request->routeIs('onboarding')) {
                return redirect()->route('dashboard.home');
            }
        }

        return $next($request);
    }
}
