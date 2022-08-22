<?php

namespace Student\App\Repo\UserRepo;

use Student\App\User\User;
use Student\App\User\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function getUser(UUID $uuid): User;
    public function getByUsername(string $username): User;
}
