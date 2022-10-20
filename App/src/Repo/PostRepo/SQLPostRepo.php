<?php

namespace Student\App\Repo\PostRepo;

use Student\App\Post\Post;
use Student\App\User\UUID;
use Student\App\Exceptions\PostNotFoundException;
use Student\App\Exceptions\PostCreatedExeption;
use PDO;
use Psr\Log\LoggerInterface;

class SQLPostRepo implements PostRepositoryInterface
{
    private PDO $connection;
    private LoggerInterface $logger;

    public function __construct(PDO $connection, LoggerInterface $logger) 
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (id, author_id, title, text)
            VALUES (:id, :author_id, :title, :text)'
        );

        $uuid = (string) $post->getId();

        $statement->execute([
            ':id' => $uuid,
            ':author_id' => (string) $post->getAuthorId(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);

        $this->logger->info("Post created: $uuid");
        // throw new PostCreatedExeption("Post successfully created");
    }

    public function getPost(?UUID $uuid): Post
    {

        if (null === $uuid) {
            $this->logger->warning("Post not found: null");
            throw new PostNotFoundException (
                "Cannot get post: null"
            );
        };

        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string) $uuid,
        ]);

        $result = $statement->fetch();

        if (false === $result) {
            $this->logger->warning("Post not found: $uuid");
            throw new PostNotFoundException (
                "Cannot get post: $uuid"
            );
        };

        return new Post(new UUID($result['id']), new UUID($result['author_id']), $result['title'], $result['text']);

    }

    public function deletePost(UUID $uuid): void
    {
        if (null === $uuid) {
            $this->logger->warning("Post not found: null");
            throw new PostNotFoundException (
                "Cannot delete post: null"
            );
        };

        // $statement = $this->connection->prepare(
        //     'DELETE FROM posts WHERE id = :id'
        // );

        // $result = $statement->execute([
        //     ':id' => (string) $uuid,
        // ]);

        $id = (string) $uuid;

        $result = $this->connection->exec("DELETE FROM posts WHERE id = ('$id')");

        if ($result == 0) {
            $this->logger->warning("Post not found: $uuid");
            throw new PostNotFoundException (
                "Cannot find post to delete: $uuid"
            );
        };
    }
}