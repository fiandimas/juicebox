<?php

namespace App\DTOs\Post;

use Illuminate\Http\Request;

class PaginationPostDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly int $userId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->query('title', ''),
            content: $request->query('content', ''),
            userId: $request->user()->id,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->userId,
        ];
    }
}
