<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LimitLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handle(Request $request, Closure $next)
    {
        $maxAttempts = 3;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
            return redirect()->route('error.too_many_attempts');
        }

        RateLimiter::hit($this->throttleKey($request), $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Get the IP
     */
    protected function throttleKey(Request $request)
    {
        return $request->ip();
    }
}
