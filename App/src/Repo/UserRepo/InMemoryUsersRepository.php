<?php

namespace Student\App\Repo\UserRepo;

use Student\App\User\User;
use Student\App\User\UUID;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function getUser(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            if ((string)$user->getId() === (string)$uuid) {
                return $user;
            }
        }

        throw new UserNotFoundException("User not found: $uuid");

    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->username() === $username) {
                return $user;
            }
        }

        throw new UserNotFoundException("User not found: $username");
    }

}
