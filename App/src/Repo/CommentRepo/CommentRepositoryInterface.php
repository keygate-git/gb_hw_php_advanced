<?php

namespace Student\App\Repo\CommentRepo;

use Student\App\Post\Comment;
use Student\App\User\UUID;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
    public function getComment(UUID $uuid): Comment;
}
