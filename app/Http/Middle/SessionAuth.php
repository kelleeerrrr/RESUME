<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionAuth
{
    /**
     * Handle an incoming request: allow if session has user_id, otherwise redirect to login.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('user_id')) {
            // remember intended url (optional)
            $request->session()->put('url.intended', $request->fullUrl());
            return redirect('/login');
        }

        return $next($request);
    }
}
