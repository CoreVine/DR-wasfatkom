<?php

namespace App\Http\Middleware;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class IsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Ensure user is authenticated before checking properties
        if (!$user) {
            return redirect()->route('login');
        }

        $routeName = $request->route()->getName();

        // If user is active and is trying to access 'pending-approval', redirect to home
        if ($user->is_active == 1 && $routeName === 'pending-approval') {
            return redirect()->route('home');
        }

        // If user is inactive and NOT already on 'pending-approval', redirect them
        if ($user->is_active == 0 && $routeName !== 'pending-approval') {
            return redirect()->route('pending-approval');
        }

        // Allow request to proceed
        return $next($request);
    }
}
