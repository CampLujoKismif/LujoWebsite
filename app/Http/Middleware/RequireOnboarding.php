<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

            if ($user->hasRole('system-admin')) {
                return $next($request);
            }

            $hasOnboardingRoute = Route::has('onboarding');

            // If onboarding is not complete and the onboarding route still exists, redirect there
            if (
                $hasOnboardingRoute &&
                !$user->onboarding_complete &&
                !$request->routeIs('onboarding') &&
                !$request->is('api/parent-onboarding/*')
            ) {
                return redirect()->route('onboarding');
            }

            // If onboarding is complete and on onboarding page, redirect to dashboard
            if ($hasOnboardingRoute && $user->onboarding_complete && $request->routeIs('onboarding')) {
                return redirect()->route('dashboard.home');
            }
        }

        return $next($request);
    }
}
