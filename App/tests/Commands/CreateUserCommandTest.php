<?php

namespace Student\App\UnitTests\Commands;

use Student\App\Commands\CreateUserCommand;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(),new DummyLogger());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: last_name');
        $command->handle(new Arguments([
        'username' => 'Ivan',
        'first_name' => 'Ivan',
        ]));
    }

}