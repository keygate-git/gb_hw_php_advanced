<?php

namespace Student\App\Repo\PostRepo;

use Student\App\Post\Post;
use Student\App\User\UUID;
use Student\App\Exceptions\PostNotFoundException;
use PDO;

class SQLPostRepo implements PostRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection) 
    {
        $this->connection = $connection;
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (id, author_id, title, text)
            VALUES (:id, :author_id, :title, :text)'
        );

        $statement->execute([
            ':id' => (string) $post->getId(),
            ':author_id' => (string) $post->getAuthorId(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    public function getPost(?UUID $uuid): Post
    {

        if (null === $uuid) {
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
            throw new PostNotFoundException (
                "Cannot get post: $uuid"
            );
        };

        return new Post(new UUID($result['id']), new UUID($result['author_id']), $result['title'], $result['text']);

    }
}