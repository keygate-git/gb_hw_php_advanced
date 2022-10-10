<?php
use Student\App\Container\DIContainer;
use Student\App\Repo\PostRepo\PostRepositoryInterface;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\Repo\UserRepo\UsersRepositoryInterface;
use Student\App\Repo\UserRepo\SQLUserRepo;
use Student\App\Repo\LikeRepo\LikeRepositoryInterface;
use Student\App\Repo\LikeRepo\SQLLikeRepo;

require_once __DIR__ . '/vendor/autoload.php';

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
    LikeRepositoryInterface::class,
    SQLLikeRepo::class
);

return $container;