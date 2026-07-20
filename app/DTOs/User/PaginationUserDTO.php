<?php

namespace App\DTOs\User;

use Illuminate\Http\Request;

class PaginationUserDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $name,
        public readonly string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            userId: $request->query('user_id', ''),
            name: $request->query('name', ''),
            email: $request->query('email', ''),
        );
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
