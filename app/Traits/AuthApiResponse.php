<?php

namespace App\Traits;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

trait AuthApiResponse {
    /**
     * Return a success JSON response.
     *
     * @param User $user
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function authResponse(User $user, int $code = 200, string $message = null): JsonResponse
    {
        // Önceki oturumlar kapatildi.
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => AuthResource::make($user)
                ->setToken(
                    'Bearer',
                    $user->createToken('API Token')->plainTextToken
                ),
        ], $code);
    }
}
