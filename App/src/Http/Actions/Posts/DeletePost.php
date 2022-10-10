<?php

namespace Student\App\Http\Actions\Posts;

use Student\App\Http\Actions\ActionInterface;
use Student\App\Http\ErrorResponse;
use Student\App\Http\Request;
use Student\App\Http\Response;
use Student\App\Http\SuccessfulResponse;
use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\PostNotFoundException;
use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\User\UUID;
use Student\App\Post\Post;

class DeletePost implements ActionInterface
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(Request $request): Response 
    {
        try {
            $postUuid = new UUID($request->query('uuid'));
        } catch (HttpExeption $e) {
            return new ErrorResponse($e->getMessage());
        }
        
        try {
            $this->postRepository->deletePost($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'uuid' => (string) $postUuid
        ]);
    }

}