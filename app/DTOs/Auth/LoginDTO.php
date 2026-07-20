<?php

namespace App\DTOs\Auth;

use App\Http\Requests\LoginRequest;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            email: $validated['title'],
            password: $validated['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
