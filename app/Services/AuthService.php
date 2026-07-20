<?php

namespace App\Services;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) { }

    public function login(LoginDTO $dto): array
    {
        $credentials = $dto->toArray();

        $user = $this->userRepository->findByEmail($credentials['email']);

        if (is_null($user) || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email or password is incorrect'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user->toArray(),
            'token' => $token,
        ];
    }

    public function register(RegisterDTO $dto): User
    {
        $user = $this->userRepository->create($dto->toArray());

        SendWelcomeEmail::dispatch($user);

        return $user;
    }
}
