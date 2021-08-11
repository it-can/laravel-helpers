<?php

namespace ITCAN\LaravelHelpers\Middleware;

use Closure;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class NoCacheHeadersMiddleware
{
    /**
     * Headers for disabling cache.
     *
     * @var array
     */
    protected $headers = [
        'pragma'        => 'no-cache',
        'expires'       => 'Thu, 19 Nov 1981 08:52:00 GMT',
        'cache-control' => 'private, max-age=0, proxy-revalidate, no-cache, must-revalidate',
    ];

    /**
     * The URIs that should be excluded from headers.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /*
         * This step is only needed if you are returning
         * a view in your Controller or elsewhere, because
         * when returning a view `$next($request)` returns
         * a View object, not a Response object, so we need
         * to wrap the View back in a Response.
        */
        if (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        }

        // Skip these urls
        if ($this->inExceptArray($request)) {
            return $response;
        }

        // Add headers to response
        foreach ($this->getHeaders() as $key => $value) {
            $response->headers->set($key, $value, true);
        }

        return $response;
    }

    /**
     * Determine if the request has a URI that should pass through.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return all headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
