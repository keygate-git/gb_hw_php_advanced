<?php

namespace Student\App\Commands;

use Student\App\Exceptions\UserNotFoundException;
use Student\App\Exceptions\CommandException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;

class CreateUserCommand
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function handle(array $input): void 
    {

        $username = $input['username'];

        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        };

        $this->usersRepository->save(new User (
            UUID::random(),
            $username,
            $input['last_name'],
            $input['first_name']
        ));            
    }

    private function userExists(string $username): bool
    {
        try {$this->usersRepository->getByUsername($username); 
        } catch (UserNotFoundException $e) {
            return false;
        }
        return true;
    }

}