<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If the user's role is in the authorized roles, proceed
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Super admins always have full access to everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Abort if unauthorized
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized access. Your role (' . $user->role . ') does not have permission to view this page.');
    }
}
