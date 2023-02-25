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
     * @param array|string|null $data
     * @param string            $message
     * @param int               $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data = [], string $message = null, int $code)
    {
        return response()
            ->json([
                'status'  => false,
                'message' => $message,
                'data'    => $data,
            ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param int               $code
     * @param array|string|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrors($data = [], string $message = null, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()
            ->json([
                'status'  => false,
                'message' => $message ?: 'Validation failed',
                'data'    => $data,
            ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContent()
    {
        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }
}
