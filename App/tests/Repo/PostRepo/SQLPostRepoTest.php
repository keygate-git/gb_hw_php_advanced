<?php

namespace Student\App\UnitTests\Repo\PostRepo;

use PHPUnit\Framework\TestCase;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\Post\Post;
use Student\App\User\UUID;
use Student\App\Commands\CreatePostCommand;
use Student\App\Exceptions\PostCreatedExeption;
use Student\App\Exceptions\PostNotFoundException;
use PDO;
use PDOStatement;


class SQLPostRepoTest extends TestCase
{
    public function testItSavesPostsToRepository(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('execute')->willReturn(true);

        $postRepository = new SQLPostRepo($connectionStub);

        $command = new CreatePostCommand($postRepository);

        $this->expectException(PostCreatedExeption::class);
        $this->expectExceptionMessage('Post successfully created');

        $command->handle([
            'author_id' => UUID::random(),
            'title' => 'title',
            'text' => 'text',
        ]);

    }

    public function testItFindsPostUsingUuid(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('fetch')->willReturn([
            'id' => '123e4567-e89b-12d3-a456-426614174000', 
            'author_id' => '123e4567-e89b-12d3-a456-426614174001', 
            'title' => 'title', 
            'text' => 'text'
        ]);

        $postRepository = new SQLPostRepo($connectionStub);

        $post = $postRepository->getPost(UUID::random());

        $value = (string) $post->getId();

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $value);
    }

    public function testItThrowsExeptionIfPostNotFound(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('fetch')->willReturn(false);

        $postRepository = new SQLPostRepo($connectionStub);

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot get post: 123e4567-e89b-12d3-a456-426614174000');

        $post = $postRepository->getPost(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }
}