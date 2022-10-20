<?php

namespace Student\App\Commands;

use Student\App\Exceptions\UserNotFoundException;
use Student\App\Exceptions\CommandException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    private UsersRepositoryInterface $usersRepository;
    private LoggerInterface $logger;

    public function __construct(UsersRepositoryInterface $usersRepository, LoggerInterface $logger)
    {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    public function handle(array $input): void 
    {

        $this->logger->info("Create user command started");

        $username = $input['username'];

        if ($this->userExists($username)) {
            // throw new CommandException("User already exists: $username");
            $this->logger->warning("User already exists: $username");
            return;
        };

        $uuid = UUID::random();

        $this->usersRepository->save(new User (
            $uuid,
            $username,
            $input['last_name'],
            $input['first_name']
        ));

        $this->logger->info("User created: $uuid");

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