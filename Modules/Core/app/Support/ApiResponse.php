<?php

namespace Modules\Core\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, array $meta = [], string $message = 'Success', int $code = 200): JsonResponse
    {
        $body = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if (! empty($meta)) {
            $body['meta'] = $meta;
        }

        return response()->json($body, $code);
    }

    public static function error(mixed $errors = null, string $message = 'Error', int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $code);
    }
}
