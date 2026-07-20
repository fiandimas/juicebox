<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) { }

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromRequest($request);
        return $this->authService->login($dto);
    }

    public function register(RegisterRequest $request)
    {
        $dto = RegisterDTO::fromRequest($request);

        return $this->authService->register($dto);
    }
}
