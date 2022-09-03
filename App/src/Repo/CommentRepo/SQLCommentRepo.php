<?php

namespace Student\App\Repo\CommentRepo;

use Student\App\Post\Comment;
use Student\App\User\UUID;
use Student\App\Exceptions\CommentNotFoundException;
use Student\App\Exceptions\CommentCreatedExeption;
use PDO;

class SQLCommentRepo implements CommentRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection) 
    {
        $this->connection = $connection;
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (id, author_id, post_id, text)
            VALUES (:id, :author_id, :post_id, :text)'
        );

        $statement->execute([
            ':id' => (string) $comment->getId(),
            ':author_id' => (string) $comment->getAuthorId(),
            ':post_id' => (string) $comment->getPostId(),
            ':text' => $comment->getText(),
        ]);

        throw new CommentCreatedExeption("Comment successfully created");
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
            throw new CommentNotFoundException (
                "Cannot get comment: $uuid"
            );
        };

        return new Comment(new UUID($result['id']), new UUID($result['author_id']), new UUID($result['post_id']), $result['text']);

    }
}