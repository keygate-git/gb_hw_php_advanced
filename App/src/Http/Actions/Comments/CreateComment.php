<?php

namespace Student\App\Http\Actions\Comments;

use Student\App\Http\Actions\ActionInterface;
use Student\App\Http\ErrorResponse;
use Student\App\Http\Request;
use Student\App\Http\Response;
use Student\App\Http\SuccessfulResponse;
use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\UserNotFoundException;
use Student\App\Exceptions\PostNotFoundException;
use Student\App\Exceptions\InvalidArgumentException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\Repo\CommentRepo\CommentRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Post\Post;
use Student\App\Post\Comment;

class CreateComment implements ActionInterface
{
    private UsersRepositoryInterface $usersRepository;
    private PostRepositoryInterface $postRepository;
    private CommentRepositoryInterface $commentRepository;

    public function __construct(UsersRepositoryInterface $usersRepository, PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
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

        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpExeption | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->postRepository->getPost(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newCommentUuid = UUID::random();

        try {
            $comment = new Comment (
                $newCommentUuid,
                $authorUuid,
                $postUuid,
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string) $newCommentUuid
        ]);
    }

}