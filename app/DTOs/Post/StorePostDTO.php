<?php

namespace App\DTOs\Post;

use App\Http\Requests\StorePostRequest;

class StorePostDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly int $userId,
    ) {}

    public static function fromRequest(StorePostRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            title: $validated['title'],
            content: $validated['content'],
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
