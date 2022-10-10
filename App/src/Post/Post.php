<?php

namespace Student\App\Post;

use Student\App\User\User;
use Student\App\User\UUID;

class Post {
    private UUID $id;
    private UUID $authorId;
    private string $title;
    private string $text;

    public function __construct(UUID $id, UUID $authorId, string $title, string $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->text = $text;
    }

    public function getTitle(): ?string
    {
        $title = $this->title;
        return $title;
    }

    public function getText(): ?string
    {
        $text = $this->text;
        return $text;
    }

    public function getId(): UUID
    {
        $id = $this->id;
        return $id;
    }

    public function getAuthorId(): UUID
    {
        $authorId = $this->authorId;
        return $authorId;
    }

}