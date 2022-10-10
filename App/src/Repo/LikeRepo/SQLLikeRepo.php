<?php

namespace Student\App\Repo\LikeRepo;

use Student\App\Post\Like;
use Student\App\User\UUID;
use Student\App\Exceptions\LikeNotFoundException;
use Student\App\Exceptions\LikeCreatedExeption;
use Student\App\Repo\LikeRepo\LikeRepositoryInterface;
use PDO;

class SQLLikeRepo implements LikeRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection) 
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (id, author_id, post_id)
            VALUES (:id, :author_id, :post_id)'
        );

        $statement->execute([
            ':id' => (string) $like->getId(),
            ':author_id' => (string) $like->getAuthorId(),
            ':post_id' => (string) $like->getPostId()
        ]);

        // throw new CommentCreatedExeption("Comment successfully created");
    } 

    public function getByPostUuid(UUID $uuid): array
    {
        $likes = [];

        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE post_id = :id'
        );
        
        $statement->execute([
            ':id' => (string) $uuid,
        ]);
        
        $likes = $statement->fetchAll();

        if (false === $likes) {
            throw new LikeNotFoundException (
                "Cannot get likes for: $uuid"
            );
        };

        return $likes;

    }
}

