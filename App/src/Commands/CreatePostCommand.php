<?php

namespace Student\App\Commands;

use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\Post\Post;
use Student\App\User\UUID;

class CreatePostCommand
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(array $input): void 
    {
        $this->postRepository->save(new Post(
            UUID::random(),
            new UUID($input['author_id']),
            $input['title'],
            $input['text']
        ));            
    }
}