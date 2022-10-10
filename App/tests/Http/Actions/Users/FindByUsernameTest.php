<?php

namespace Student\App\UnitTests\Http\Actions\Users;

use PHPUnit\Framework\TestCase;
use Student\App\Http\Actions\Users\FindByUsername;
use Student\App\Http\ErrorResponse;
use Student\App\Http\Request;
use Student\App\Http\Response;
use Student\App\Http\SuccessfulResponse;
use Student\App\Exceptions\UserNotFoundException;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\User\User;
use Student\App\User\UUID;

class FindByUsernameTest extends TestCase 
{
    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void 
    {
        $request = new Request([], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: $param"}');

        $response->send();

    }

    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */

    public function testItReturnsErrorResponseIfUserNotFound(): void 
    {
        $request = new Request(['username'=>'ivan'], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');

        $response->send();
    }

    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */

    public function testItReturnsSuccessfulResponse(): void 
    {
        $request = new Request(['username'=>'ivan'], [], '');

        $usersRepository = $this->usersRepository([
            new User(
                new UUID('16db96a8-80a9-44e0-a736-2fc2adbcc791'),
                'ivan',
                'Nikitin',
                'Ivan'
            )
        ]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);

        $this->expectOutputString('{"success":true,"data":{"username":"ivan","name":"Ivan Nikitin","uuid":"16db96a8-80a9-44e0-a736-2fc2adbcc791"}}');

        $response->send();
    }

    private function usersRepository(array $users): UsersRepositoryInterface 
    {
        return new class($users) implements UsersRepositoryInterface 
        {
            private array $users = [];

            public function __construct(array $users) 
            {
                $this->users = $users;
            }

            public function save(User $user): void 
            {

            }

            public function getUser(UUID $uuid): User 
            {
                throw new UserNotFoundException('User not found');
            }

            public function getByUsername(string $username): User 
            {
                foreach ($this->users as $user) {
                    if($user instanceof User && $username === $user->getUsername())
                    {
                        return $user;
                    }
                }

                throw new UserNotFoundException('Not found');
            }
        };
    }
}