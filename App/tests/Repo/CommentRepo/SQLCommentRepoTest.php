<?php

namespace Student\App\UnitTests\Repo\CommentRepo;

use PHPUnit\Framework\TestCase;
use Student\App\Repo\CommentRepo\SQLCommentRepo;
use Student\App\Post\Comment;
use Student\App\Post\Post;
use Student\App\User\UUID;
use Student\App\Commands\CreateCommentCommand;
use Student\App\Exceptions\CommentCreatedExeption;
use Student\App\Exceptions\CommentNotFoundException;
use PDO;
use PDOStatement;


class SQLCommentRepoTest extends TestCase
{
    public function testItSavesCommentToRepository(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('execute')->willReturn(true);

        $postRepository = new SQLCommentRepo($connectionStub);

        $command = new CreateCommentCommand($postRepository);

        $this->expectException(CommentCreatedExeption::class);
        $this->expectExceptionMessage('Comment successfully created');

        $command->handle([
            'author_id' => UUID::random(),
            'post_id' => UUID::random(),
            'text' => 'text',
        ]);

    }

    public function testItFindsCommentUsingUuid(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('fetch')->willReturn([
            'id' => '123e4567-e89b-12d3-a456-426614174000', 
            'author_id' => '123e4567-e89b-12d3-a456-426614174001', 
            'post_id' => '123e4567-e89b-12d3-a456-426614174002', 
            'text' => 'text'
        ]);

        $postRepository = new SQLCommentRepo($connectionStub);

        $comment = $postRepository->getComment(UUID::random());

        $value = (string) $comment->getId();

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $value);
    }

    public function testItThrowsExeptionIfCommentNotFound(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('fetch')->willReturn(false);

        $postRepository = new SQLCommentRepo($connectionStub);

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot get comment: 123e4567-e89b-12d3-a456-426614174000');

        $post = $postRepository->getComment(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }
}