<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'admin' role
        if ($request->user() && $request->user()->role !== 'admin') {
            // If not an admin, redirect to the user dashboard or any other page
            return redirect()->route('user-dashboard')->with('error', 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
