<?php

namespace App\Http\Middleware;

use Closure;

class EnableDebugMode
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('__DEBUG__') && $request->get('__DEBUG__') === '1') {
            config()->set('app.debug', true);
        }

        return $next($request);
    }
}
