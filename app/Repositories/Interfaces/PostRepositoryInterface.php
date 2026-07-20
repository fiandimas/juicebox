<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function create(array $data): Post;
    public function paginate(array $filters = []): LengthAwarePaginator;
    public function update(Post $post, array $data): bool;
}
