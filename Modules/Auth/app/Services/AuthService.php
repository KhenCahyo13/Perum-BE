<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;
use Modules\Auth\Repositories\UserRepository;
use Modules\Core\Services\Service;

class AuthService extends Service
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user instanceof User || ! Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'data' => $user,
            'meta' => [
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function refresh(User $user): array
    {
        $user->currentAccessToken()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'data' => $user,
            'meta' => [
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }
}
