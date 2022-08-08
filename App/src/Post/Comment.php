<?php

namespace Student\App\Post;

use Student\App\Post\Post;
use Student\App\User\User;

class Comment extends Post{
    private string $postId;

    public function __construct(User $user, Post $post, $text)
    {
        $this->id = 1;
        $this->authorId = $user->getId();
        $this->postId = $post->getId();
        $this->text = $text;
    }

    public function getText(): string
    {
        $text = $this->text;
        return $text;
    }

}