<?php

namespace App\Http\Middleware;

use Closure;
use App\SEO\WithSEOMeta;
use App\SEO\SEOConfigure;
use Artesaos\SEOTools\Facades\SEOTools;

class TryingAutoConfigSEO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        SeoTools::setCanonical(url()->current());

        foreach ($request->route()->parameters() as $model) {
            if ($model instanceof WithSEOMeta) {
                SEOConfigure::config($model);
                break;
            }
        }

        return $next($request);
    }
}
