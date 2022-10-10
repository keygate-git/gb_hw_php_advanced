<?php

namespace Student\App\Repo\LikeRepo;

use Student\App\Post\Like;
use Student\App\User\UUID;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
    public function getComment(UUID $uuid): Comment;
}