<?php

namespace ITCAN\LaravelHelpers\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param array|string $data
     * @param string       $message
     * @param int|null     $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = null, int $code = Response::HTTP_OK)
    {
        return response()
            ->json([
                'status'  => true,
                'message' => $message,
                'data'    => $data,
            ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string            $message
     * @param int               $code
     * @param array|string|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code, $data = [])
    {
        return response()
            ->json([
                'status'  => false,
                'message' => $message,
                'data'    => $data,
            ], $code);
    }

}