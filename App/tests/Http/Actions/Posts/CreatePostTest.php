<?php

namespace Student\App\UnitTests\Http\Actions\Posts;

use PHPUnit\Framework\TestCase;
use Student\App\Http\Request;
use Student\App\Http\SuccessfulResponse;
use Student\App\Http\ErrorResponse;
use Student\App\Repo\UserRepo\SQLUserRepo;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Http\Actions\Posts\CreatePost;
use PDO;
use PDOStatement;

class CreatePostTest extends TestCase 
{
    /**
        * @runInSeparateProcess
        * @preserveGlobalState disabled
    */

    public function testItReturnsSuccessfulResponse(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $request = new Request([], [], '{
            "author_uuid": "a4b4601e-736d-4098-a2d6-13097614af83",
            "text": "some text",
            "title": "some title"
        }');

        $userRepository = $this->createStub(SQLUserRepo::class);

        $postReporitory = new SQLPostRepo($connectionStub);

        $user = new User (
            new UUID('a4b4601e-736d-4098-a2d6-13097614af83'),
            'ivan',
            'Nikitin',
            'Ivan'
        );

        $userRepository->method('getUser')->willReturn($user);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('execute')->willReturn(true);

        $action = new CreatePost($userRepository, $postReporitory);

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        
    } 

    public function testItSendsErrorResponseIfUuidIsWrongType(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $userRepository = new SQLUserRepo($connectionStub);

        $postReporitory = new SQLPostRepo($connectionStub);

        $request = new Request([], [], '{
            "author_uuid": false,
            "text": "some text",
            "title": "some title"
        }');

        $action = new CreatePost($userRepository, $postReporitory);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
    }

    public function testItSendsErrorResponseIfUserNotFound(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $statementStub->method('execute')->willReturn(true);

        $statementStub->method('fetch')->willReturn(false);

        $request = new Request([], [], '{
            "author_uuid": "a4b4601e-736d-4098-a2d6-13097614af83",
            "text": "some text",
            "title": "some title"
        }');

        $userRepository = new SQLUserRepo($connectionStub);

        $postReporitory = new SQLPostRepo($connectionStub);

        $action = new CreatePost($userRepository, $postReporitory);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
    }

    public function testItItSendsErrorResponseIfItTooFewArguments(): void 
    {
        $connectionStub = $this->createStub(PDO::class);

        $request = new Request([], [], '{
            "author_uuid": ,
            "text": "some text",
            "title": "some title"
        }');

        $userRepository = new SQLUserRepo($connectionStub);

        $postReporitory = new SQLPostRepo($connectionStub);

        $action = new CreatePost($userRepository, $postReporitory);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
    }
}