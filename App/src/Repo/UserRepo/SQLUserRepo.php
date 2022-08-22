<?php

namespace Student\App\Repo\UserRepo;

use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Exceptions\UserNotFoundException;
use PDO;

class SQLUserRepo implements UsersRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection) 
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (id, username, first_name, last_name)
            VALUES (:id, :username, :first_name, :last_name)'
        );

        $statement->execute([
            ':id' => (string) $user->getId(),
            ':username' => $user->getUsername(),
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
        ]);
    }

    public function getUser(UUID $uuid): User 
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE id = ?'
        );

        $statement->execute([
            ':id' => (string) $uuid,
        ]);

        $result = $statement->fetch();

        if (false === $result) {
            throw new UserNotFoundException (
                "Cannot get user: $uuid"
            );
        };

        return new User (new UUID($result['id']), $result['username'], $result['first_name'], $result['last_name']);

    }

    public function getByUsername(?string $username): User
    {

        if (null === $username) {
            throw new UserNotFoundException (
                "Cannot get user: null"
            );
        };

        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );

        $statement->execute([
            ':username' => $username,
        ]);

        $result = $statement->fetch();

        if (false === $result) {
            throw new UserNotFoundException (
                "Cannot get user: $username"
            );
        };

        return new User (new UUID($result['id']), $result['username'], $result['first_name'], $result['last_name']);

    }
}