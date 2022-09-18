<?php

namespace Student\App\Http\Actions\Users;

use Student\App\Http\Actions\ActionInterface;
use Student\App\Http\ErrorResponse;
use Student\App\Http\Request;
use Student\App\Http\Response;
use Student\App\Http\SuccessfulResponse;
use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\UserNotFoundException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\User\User;

class FindByUsername implements ActionInterface
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function handle(Request $request): Response 
    {
        try {
            $username = $request->query('username');
        } catch (HttpExeption $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'username' => $user->getUsername(),
            'name' => $user->getFirstName() . ' ' . $user->getLastName(),
            'uuid' => (string) $user->getId()
        ]);
    }
}
