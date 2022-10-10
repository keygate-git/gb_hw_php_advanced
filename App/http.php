<?php

use Student\App\Http\Request;
use Student\App\Http\SuccessfulResponse;
use Student\App\Http\ErrorResponse;
use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\AppException;
use Student\App\Http\Actions\Users\FindByUsername;
use Student\App\Http\Actions\Posts\CreatePost;
use Student\App\Http\Actions\Posts\DeletePost;
use Student\App\Http\Actions\Likes\CreateLike;
use Student\App\Http\Actions\Comments\CreateComment;
use Student\App\Repo\UserRepo\SQLUserRepo;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\Repo\CommentRepo\SQLCommentRepo;
use Student\App\Repo\LikeRepo\SQLLikeRepo;

require_once __DIR__ . '/vendor/autoload.php';

// $connection = require 'db.php';

$container = require __DIR__ . '/bootstrap.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

// $parameter = $request->query('some_parameter');
// $header = $request->header('Some-Header');
// $path = $request->path();

// $response = new SuccessfulResponse([
//     'message' => 'Hello from PHP'
// ]);

// $response->send();

// header('Some-Header:' . $parameter);
// header('One-More-Header:' . $header);
// header('Another-Header:' . $path);

try {
    $path = $request->path();
} catch (HttpException $e) {
    (new ErrorResponse)->send();

    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        //'/users/show' => new FindByUsername(new SQLUserRepo($connection)),
        '/users/show' => FindByUsername::class,
        // '/posts/show' => new FindByUuid(new SQLPostRepo($connection))
    ],
    'POST' => [
        //'/posts/create' => new CreatePost(new SQLUserRepo($connection), new SQLPostRepo($connection)),
        '/posts/create' => CreatePost::class,
        //'/posts/comment' => new CreateComment(new SQLUserRepo($connection), new SQLPostRepo($connection), new SQLCommentRepo($connection))
        '/posts/comment' => CreateComment::class,
        '/posts/like' => CreateLike::class,
    ],
    'DELETE' => [
        //'/posts' => new DeletePost(new SQLPostRepo($connection))
        '/posts' => DeletePost::class
    ]
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Method not Found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Path not Found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
