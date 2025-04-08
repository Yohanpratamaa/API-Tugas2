<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;

class CustomErrorHandler
{
    public static function handleException(\Exception $e, string $message, int $statusCode): JsonResponse
    {
        Log::error($message . ': ' . $e->getMessage());
        return ApiResponse::error($message, $statusCode);
    }
}
