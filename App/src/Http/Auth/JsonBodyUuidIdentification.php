<?php

namespace Student\App\Http\Auth;

use Student\App\Http\Auth\IdentificationInterface;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\User\User;
use Student\App\Http\Request;
use Student\App\User\UUID;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository) 
    {
        $this->usersRepository = $usersRepository;
    }

    public function user(Request $request): User
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException|InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->usersRepository->getUser($userUuid);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
        
}