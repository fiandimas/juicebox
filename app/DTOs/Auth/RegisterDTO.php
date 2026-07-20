<?php

namespace App\DTOs\Auth;

use App\Http\Requests\RegisterRequest;

class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            name: $validated['name'],
            email: $validated['title'],
            password: $validated['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
