<?php

namespace Student\App\Post;

use Student\App\Post\Post;
use Student\App\User\User;
use Student\App\User\UUID;

class Like
{
    private UUID $id;
    private UUID $authorId;
    private UUID $postId;

    public function __construct(UUID $id, UUID $authorId, UUID $postId)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->postId = $postId;
    }

    public function getId(): UUID
    {
        $id = $this->id;
        return $id;
    }

    public function getPostId(): UUID
    {
        $postId = $this->postId;
        return $postId;
    }

    public function getAuthorId(): UUID
    {
        $authorId = $this->authorId;
        return $authorId;
    }

}