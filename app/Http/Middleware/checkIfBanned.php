<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Route
};

class checkIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $checkForBan = auth()->user()->bans()->active()->exists();

            $acceptedRoutes = [
                'suspended',
                'suspended.reactivate',
                'logout',
                'notes',
            ];

            if ($checkForBan && !in_array(Route::currentRouteName(), $acceptedRoutes))
                return redirect()->route('suspended');
        }
        return $next($request);
    }
}
