<?php

namespace ITCAN\LaravelHelpers\Middleware;

use Closure;
use RuntimeException;

class ValidateJson
{
    /**
     * The HTTP verbs that should be validated.
     *
     * @var array
     */
    protected $methodsToParse = [
        'DELETE',
        'PATCH',
        'POST',
        'PUT',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! in_array($request->getMethod(), $this->methodsToParse)) {
            return $next($request);
        }

        json_decode($request->getContent());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Unable to parse JSON data: '
                .json_last_error_msg()
            );
        }

        return $next($request);
    }
}
