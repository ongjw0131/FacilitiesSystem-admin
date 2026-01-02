<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentOnly
{
    /**
     * Handle an incoming request.
     * Allows only students to access, denies admins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Please log in first.');
        }

        // Check if user is admin
        if (Auth::user()->role === 'admin') {
            abort(403, 'Admins cannot access this page. This area is for students only.');
        }

        return $next($request);
    }
}
