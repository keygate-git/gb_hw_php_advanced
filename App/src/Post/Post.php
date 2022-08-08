<?php

namespace Student\App\Post;

use Student\App\User\User;

class Post {
    private int $id;
    private string $authorId;
    private string $title;
    private string $text;

    public function __construct(User $user, string $title, string $text)
    {
        $this->id = 1;
        $this->authorId = $user->getId();
        $this->title = $title;
        $this->text = $text;
    }

    public function getData(): ?string
    {
        $data = $this->title . " >>> " . $this->text;
        return $data;
    }

    public function getId(): int
    {
        $id = $this->id;
        return $id;
    }

}