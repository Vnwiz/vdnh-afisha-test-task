<?php

namespace App\Shared\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Успешный ответ.
     */
    public static function success(
        mixed $data = null,
        ?string $message = null,
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Успешный ответ при создании ресурса (201).
     */
    public static function created(
        mixed $data = null,
        ?string $message = null
    ): JsonResponse {
        return self::success(
            data: $data,
            message: $message,
            status: 201
        );
    }

    /**
     * Ошибочный ответ.
     */
    public static function error(
        string $message,
        int $status = 400,
        mixed $data = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
