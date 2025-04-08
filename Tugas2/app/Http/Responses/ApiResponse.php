<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data, string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'status_code' => $statusCode,
            'message' => $message,
            'data' => null,
        ], $statusCode);
    }
}
