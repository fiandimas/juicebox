<?php

namespace App\Http\Controllers;

use App\DTOs\Post\StorePostDTO;
use App\DTOs\Post\PaginationPostDTO;
use App\DTOs\Post\UpdatePostDTO;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) { }

    #[Authorize('view', 'post')]
    public function index(Request $request)
    {
        $dto = PaginationPostDTO::fromRequest($request);
        $posts = $this->postService->paginate($dto);
        
        return PostResource::collection($posts);
    }

    #[Authorize('create', 'post')]
    public function store(StorePostRequest $request)
    {
        $dto = StorePostDTO::fromRequest($request);
        $user = $this->postService->create($dto);

        return response(new PostResource($user), 201);
    }

    #[Authorize('view', 'post')]
    public function show(Post $post)
    {
        return new PostResource($post->load('user'));
    }

    #[Authorize('update', 'post')]
    public function update(Post $post, UpdatePostRequest $request)
    {
        $dto = UpdatePostDTO::fromRequest($request);
        $post = $this->postService->update($post, $dto);

        return new PostResource($post->load('user'));
    }

    #[Authorize('delete', 'post')]
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
