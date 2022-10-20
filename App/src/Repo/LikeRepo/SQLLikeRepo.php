<?php

namespace Student\App\Repo\LikeRepo;

use Student\App\Post\Like;
use Student\App\User\UUID;
use Student\App\Exceptions\LikeNotFoundException;
use Student\App\Exceptions\LikeCreatedExeption;
use Student\App\Repo\LikeRepo\LikeRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class SQLLikeRepo implements LikeRepositoryInterface
{
    private PDO $connection;
    private LoggerInterface $logger;

    public function __construct(PDO $connection, LoggerInterface $logger) 
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (id, author_id, post_id)
            VALUES (:id, :author_id, :post_id)'
        );

        $uuid = (string) $like->getId();

        $statement->execute([
            ':id' => $uuid,
            ':author_id' => (string) $like->getAuthorId(),
            ':post_id' => (string) $like->getPostId()
        ]);

        $this->logger->info("Like created: $uuid");
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
            $this->logger->warning("Likes not found for: $uuid");
            throw new LikeNotFoundException (
                "Cannot get likes for: $uuid"
            );
        };

        return $likes;

    }
}

