<?php

namespace Student\App\Post;

use Student\App\Post\Post;
use Student\App\User\User;
use Student\App\User\UUID;

class Comment {
    private UUID $id;
    private UUID $authorId;
    private UUID $postId;
    private string $text;

    public function __construct(UUID $id, UUID $authorId, UUID $postId, string $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->postId = $postId;
        $this->text = $text;
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

    public function getText(): string
    {
        $text = $this->text;
        return $text;
    }

}