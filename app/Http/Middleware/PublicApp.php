<?php

namespace App\Http\Middleware;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        
        return $next($request);
    }
}
