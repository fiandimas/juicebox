<?php

namespace App\Services;

use App\DTOs\Post\StorePostDTO;
use App\DTOs\Post\PaginationPostDTO;
use App\DTOs\Post\UpdatePostDTO;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) { }

    public function create(StorePostDTO $dto): Post
    {
        return $this->postRepository->create($dto->toArray());
    }

    public function paginate(PaginationPostDTO $dto): LengthAwarePaginator
    {
        return $this->postRepository->paginate($dto->toArray());
    }

    public function update(Post $post, UpdatePostDTO $dto): Post
    {
        $this->postRepository->update($post, $dto->toArray());
        return $post->refresh();
    }
}
