<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return User::withCount('posts')
            ->when(isset($filters['id']),       fn ($q) => $q->where('id', 'LIKE', $filters['id']))
            ->when(isset($filters['email']),    fn ($q) => $q->where('email', 'LIKE', '%'. $filters['email'] .'%'))
            ->when(isset($filters['name']),     fn ($q) => $q->where('name', 'LIKE', '%'. $filters['name'] .'%'))
            ->latest()
            ->paginate(15);
    }

    public function findByEmail(string $email): ?User {
        return User::where('email', $email)->first();
    }
}