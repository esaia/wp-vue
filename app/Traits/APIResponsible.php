<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use function response;

trait APIResponsible
{
    public function success(mixed $message = null, mixed $data = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->baseResponse($message, $data, $code);
    }

    private function baseResponse(
        mixed $message = null,
        mixed  $data = null,
        int    $code = Response::HTTP_OK
    ): JsonResponse {
        if ($data instanceof AnonymousResourceCollection) {
            return $data->additional(array_merge($data->additional, ['message' => $message]))
                ->response()
                ->setStatusCode($code);
        } else {
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], $code);
        }
    }

    public function error(
        string $message,
        mixed  $data = null,
        int    $code = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        return $this->baseResponse($message, $data, $code);
    }
}
