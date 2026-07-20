<?php

namespace App\Services;

use App\DTOs\User\PaginationUserDTO;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) { }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function paginate(PaginationUserDTO $dto): LengthAwarePaginator
    {
        return $this->userRepository->paginate($dto->toArray());
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
