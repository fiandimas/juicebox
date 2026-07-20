<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) { }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
