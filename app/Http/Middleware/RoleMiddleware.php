<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Redirect to their own dashboard if wrong role
        return redirect(match($user->role) {
            'admin'      => '/admin/dashboard',
            'faculty'    => '/faculty/dashboard',
            'researcher' => '/research/dashboard',
            default      => '/login',
        })->with('error', 'You do not have permission to access that page.');
    }
}
