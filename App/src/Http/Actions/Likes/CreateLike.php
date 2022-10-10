<?php

namespace Student\App\Http\Actions\Likes;

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
use Student\App\Repo\LikeRepo\LikeRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Post\Post;
use Student\App\Post\Like;

class CreateLike implements ActionInterface
{
    private UsersRepositoryInterface $usersRepository;
    private PostRepositoryInterface $postRepository;
    private LikeRepositoryInterface $likeRepository;

    public function __construct(UsersRepositoryInterface $usersRepository, PostRepositoryInterface $postRepository, LikeRepositoryInterface $likeRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->postRepository = $postRepository;
        $this->likeRepository = $likeRepository;
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

        try {
            $likes = $this->likeRepository->getByPostUuid(new UUID($postUuid));
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $isLiked = false;

        foreach($likes as $like) {
            if ($like["author_id"] == $authorUuid) {
                $isLiked = true;
                break;
            }
        }

        if(!$isLiked) {

        $newLikeUuid = UUID::random();

        try {
            $like = new Like (
                $newLikeUuid,
                $authorUuid,
                $postUuid
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->likeRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => (string) $newLikeUuid
        ]);

        } else {
            return new ErrorResponse('Like already has been created by this author');
        }
    }

}