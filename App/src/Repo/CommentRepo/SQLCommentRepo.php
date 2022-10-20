<?php

namespace Student\App\Repo\CommentRepo;

use Student\App\Post\Comment;
use Student\App\User\UUID;
use Student\App\Exceptions\CommentNotFoundException;
use Student\App\Exceptions\CommentCreatedExeption;
use Student\App\Repo\CommentRepo\CommentRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class SQLCommentRepo implements CommentRepositoryInterface
{
    private PDO $connection;
    private LoggerInterface $logger;

    public function __construct(PDO $connection, LoggerInterface $logger) 
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (id, author_id, post_id, text)
            VALUES (:id, :author_id, :post_id, :text)'
        );

        $uuid = (string) $comment->getId();

        $statement->execute([
            ':id' => $uuid,
            ':author_id' => (string) $comment->getAuthorId(),
            ':post_id' => (string) $comment->getPostId(),
            ':text' => $comment->getText(),
        ]);

        $this->logger->info("Post created: $uuid");
        // throw new CommentCreatedExeption("Comment successfully created");
    }

    public function getComment(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string) $uuid,
        ]);

        $result = $statement->fetch();

        if (false === $result) {
            $this->logger->warning("Comment not found: $uuid");
            throw new CommentNotFoundException (
                "Cannot get comment: $uuid"
            );
        };

        return new Comment(new UUID($result['id']), new UUID($result['author_id']), new UUID($result['post_id']), $result['text']);

    }
}