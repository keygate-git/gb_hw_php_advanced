<?php

namespace Student\App\Repo\PostRepo;

use Student\App\Post\Post;
use Student\App\User\UUID;
use Student\App\Exceptions\PostNotFoundException;
use Student\App\Exceptions\PostCreatedExeption;
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

        // throw new PostCreatedExeption("Post successfully created");
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

    public function deletePost(UUID $uuid): void
    {
        if (null === $uuid) {
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
            throw new PostNotFoundException (
                "Cannot find post to delete: $uuid"
            );
        };
    }
}