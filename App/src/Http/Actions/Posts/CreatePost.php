<?php

namespace Student\App\Http\Actions\Posts;

use Student\App\Http\Actions\ActionInterface;
use Student\App\Http\ErrorResponse;
use Student\App\Http\Request;
use Student\App\Http\Response;
use Student\App\Http\SuccessfulResponse;
use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\UserNotFoundException;
use Student\App\Exceptions\InvalidArgumentException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Post\Post;

class CreatePost implements ActionInterface
{
    private UsersRepositoryInterface $usersRepository;
    private PostRepositoryInterface $postRepository;

    public function __construct(UsersRepositoryInterface $usersRepository, PostRepositoryInterface $postRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->postRepository = $postRepository;
    }

    public function handle(Request $request): Response 
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpExeption | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->usersRepository->getUser(new UUID($authorUuid));
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();

        try {
            $post = new Post (
                $newPostUuid,
                $authorUuid,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postRepository->save($post);

        return new SuccessfulResponse([
            'uuid' => (string) $newPostUuid
        ]);
    }
}