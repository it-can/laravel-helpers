<?php

namespace ITCAN\LaravelHelpers\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = null, int $code = Response::HTTP_OK)
    {
        return response()
            ->json([
                'status' => true,
                'message' => $message,
                'data' => $data,
            ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data, string $message = null, int $code)
    {
        return response()
            ->json([
                'status' => false,
                'message' => $message,
                'data' => $data,
            ], $code);
    }

    /**
     * Return an error JSON response from validation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrors($errors = [], string $message = null, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()
            ->json([
                'status' => false,
                'message' => $message ?: 'Validation failed',
                'errors' => $errors,
            ], $code);
    }

    /**
     * Return no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContent()
    {
        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }
}
