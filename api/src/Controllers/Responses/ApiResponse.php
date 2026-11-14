<?php

declare(strict_types=1);

namespace ADS\TakeHome\Controllers\Responses;

use ADS\TakeHome\Framework\Http\JsonHttpResponse;

final class ApiResponse
{
    public static function ok(mixed $data): JsonHttpResponse
    {
        return new JsonHttpResponse($data, 200);
    }

    /**
     * @param array<string, string[]> $errors
     */
    public static function validationError(array $errors): JsonHttpResponse
    {
        return new JsonHttpResponse([
            'message' => 'Validation failed.',
            'errors' => $errors,
        ], 422);
    }

    public static function notFound(string $message): JsonHttpResponse
    {
        return new JsonHttpResponse(['message' => $message], 404);
    }
}
