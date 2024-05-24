<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;

class RestrictedArea
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
        if (!$request->user()->isSuperAdmin()) {
            abort(404);
        }

        try {
            $decrypted = $request->get('Secret');
        } catch (DecryptException $e) {
            $decrypted = '';
        }

        if ('secret' !== $decrypted) {
            abort(412, 'Wrong!');
        }

        return $next($request);
    }
}
