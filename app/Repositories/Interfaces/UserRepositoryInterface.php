<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function paginate(array $filters = []): LengthAwarePaginator;
    public function findByEmail(string $email): ?User;
}
