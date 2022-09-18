<?php

namespace Student\App\Repo\PostRepo;

use Student\App\Post\Post;
use Student\App\User\UUID;

interface PostRepositoryInterface
{
    public function save(Post $post): void;
    public function getPost(UUID $uuid): Post;
    public function deletePost(UUID $uuid): void;
}
