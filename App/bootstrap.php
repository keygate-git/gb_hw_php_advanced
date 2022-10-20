<?php
use Student\App\Container\DIContainer;
use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\Repo\UserRepo\SQLUserRepo;
use Student\App\Repo\CommentRepo\CommentRepositoryInterface;
use Student\App\Repo\CommentRepo\SQLCommentRepo;
use Student\App\Repo\LikeRepo\LikeRepositoryInterface;
use Student\App\Repo\LikeRepo\SQLLikeRepo;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Student\App\Http\Auth\IdentificationInterface;
use Student\App\Http\Auth\JsonBodyUuidIdentification;

require_once __DIR__ . '/vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:database.db',null, null,[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC])
);

$container->bind(
    PostRepositoryInterface::class,
    SQLPostRepo::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SQLUserRepo::class
);

$container->bind(
    CommentRepositoryInterface::class,
    SQLCommentRepo::class
);

$container->bind(
    LikeRepositoryInterface::class,
    SQLLikeRepo::class
);

$container->bind(
    LoggerInterface::class,
    (new Logger('blog'))
    ->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log')
    )
    ->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.error.log', 
    Logger::ERROR, 
    false,)
    )
    ->pushHandler(new StreamHandler("php://stdout")
    )
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);
       
return $container;