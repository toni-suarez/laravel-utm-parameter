<?php

namespace Suarez\UtmParameter\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Suarez\UtmParameter\UtmParameter;

class UtmParameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldAcceptUtmParameter($request, $response)) {
            app(UtmParameter::class)->boot(session('utm'));
        }

        return $response;
    }

    /**
     * Determines whether the given request/response pair should accept UTM-Parameters.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     * @return bool
     */
    protected function shouldAcceptUtmParameter(Request $request, Response $response)
    {
        return $request->isMethod('GET') && $response->getStatusCode() == 200;
    }
}
