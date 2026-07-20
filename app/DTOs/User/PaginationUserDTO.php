<?php

namespace App\DTOs\User;

use Illuminate\Http\Request;

class PaginationUserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            id: $request->query('id', ''),
            name: $request->query('name', ''),
            email: $request->query('email', ''),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
