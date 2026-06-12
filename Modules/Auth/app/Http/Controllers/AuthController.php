<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Services\AuthService;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->input('email'),
            $request->input('password'),
        );

        if ($result === null) {
            return ApiResponse::error(null, 'Email atau password salah.', 401);
        }

        return ApiResponse::success($result['data'], $result['meta'], 'Login berhasil.');
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->authService->logout($user);

        return ApiResponse::success(null, message: 'Logout berhasil.');
    }

    public function refresh(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $result = $this->authService->refresh($user);

        return ApiResponse::success($result['data'], $result['meta'], 'Token berhasil diperbarui.');
    }
}
