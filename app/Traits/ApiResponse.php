<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse {
    /**
     * Return a success JSON response.
     *
     * @param ResourceCollection|JsonResource|null $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success(ResourceCollection|JsonResource|null $data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @return JsonResponse
     */
    protected function error(int $code = 500, ?string $message = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
