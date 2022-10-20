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
use Psr\Log\LoggerInterface;
use Student\App\Http\Auth\IdentificationInterface;

class CreatePost implements ActionInterface
{
    private IdentificationInterface $identification;
    private PostRepositoryInterface $postRepository;
    private LoggerInterface $logger;

    public function __construct(IdentificationInterface $identification, PostRepositoryInterface $postRepository, LoggerInterface $logger)
    {
        $this->identification = $identification;
        $this->postRepository = $postRepository;
        $this->logger = $logger;
    }

    public function handle(Request $request): Response 
    {
        // try {
        //     $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        // } catch (HttpExeption | InvalidArgumentException $e) {
        //     return new ErrorResponse($e->getMessage());
        // }

        // try {
        //     $this->usersRepository->getUser(new UUID($authorUuid));
        // } catch (UserNotFoundException $e) {
        //     return new ErrorResponse($e->getMessage());
        // }

        $author = $this->identification->user($request);

        $newPostUuid = UUID::random();

        try {
            $post = new Post (
                $newPostUuid,
                // $authorUuid,
                $author->getId(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postRepository->save($post);

        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string) $newPostUuid
        ]);
    }
}