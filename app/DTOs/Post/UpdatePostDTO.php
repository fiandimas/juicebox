<?php

namespace App\DTOs\Post;

use App\Http\Requests\UpdatePostRequest;

class UpdatePostDTO
{
    public function __construct(
        public readonly null|string $title,
        public readonly null|string $content,
    ) {}

    public static function fromRequest(UpdatePostRequest $request): self
    {
        return new self(
            title: $request->input('title', null),
            content: $request->input('content', null),
        );
    }

    public function toArray(): array
    {
        return filter_null([
            'title' => $this->title,
            'content' => $this->content,
        ]);
    }
}
