<?php

namespace Suarez\UtmParameter\Middleware;

use Closure;
use Illuminate\Http\Request;
use Suarez\UtmParameter\Facades\UtmParameter;

class UtmParameters
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldAcceptUtmParameter($request)) {
            UtmParameter::boot($request);
        }

        return $next($request);
    }

    /**
     * Determines whether the given request/response pair should accept UTM-Parameters.
     */
    protected function shouldAcceptUtmParameter(Request $request): bool
    {
        return $request->isMethod('GET');
    }
}
