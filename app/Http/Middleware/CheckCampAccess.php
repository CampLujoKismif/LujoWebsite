<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCampAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        $campId = $request->route('camp') ?? $request->input('camp_id');

        if (!$campId) {
            abort(400, 'Camp ID is required.');
        }

        if (!$request->user() || !$request->user()->canAccessCampData($campId, $permission)) {
            abort(403, 'You do not have access to this camp.');
        }

        return $next($request);
    }
} 