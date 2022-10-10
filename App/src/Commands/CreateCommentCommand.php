<?php

namespace Student\App\Commands;

use Student\App\Repo\CommentRepo\CommentRepositoryInterface;
use Student\App\Post\Comment;
use Student\App\User\UUID;

class CreateCommentCommand
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(array $input): void 
    {
        $this->commentRepository->save(new Comment(
            UUID::random(),
            new UUID($input['author_id']),
            new UUID($input['post_id']),
            $input['text']
        ));            
    }
}