<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Post::with('user')
            ->when(filled($filters['title']),    fn ($q) => $q->where('title', 'LIKE', '%'. $filters['title'] .'%'))
            ->when(filled($filters['content']),  fn ($q) => $q->where('content', 'LIKE', '%'. $filters['content'] .'%'))
            ->latest()
            ->paginate(15);
    }

    public function update(Post $post, array $data): bool
    {
        return $post->update($data);
    }
}
