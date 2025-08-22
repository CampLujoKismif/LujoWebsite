<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role, string ...$additionalRoles): Response
    {
        $roles = array_merge([$role], $additionalRoles);

        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasAnyRole($roles)) {
            abort(403, 'Access denied. You do not have the required role.');
        }

        return $next($request);
    }
} 