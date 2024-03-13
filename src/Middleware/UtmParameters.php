<?php

namespace Suarez\UtmParameter\Middleware;

use Closure;
use Illuminate\Http\Request;
use Suarez\UtmParameter\UtmParameter;

class UtmParameters
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldAcceptUtmParameter($request)) {
            app(UtmParameter::class)->boot(session('utm'));
        }

        return $next($request);
    }

    /**
     * Determines whether the given request/response pair should accept UTM-Parameters.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     *
     * @return \Illuminate\Http\Request
     */
    protected function shouldAcceptUtmParameter(Request $request)
    {
        return $request->isMethod('GET');
    }
}
