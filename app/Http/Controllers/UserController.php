<?php

namespace App\Http\Controllers;

use App\DTOs\User\PaginationUserDTO;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) { }

    public function index(Request $request)
    {
        $dto = PaginationUserDTO::fromRequest($request);
        $users = $this->userService->paginate($dto);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user->loadCount('posts'));
    }
}
