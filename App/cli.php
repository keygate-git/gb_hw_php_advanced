<?php

require_once __DIR__ . '/vendor/autoload.php';

use Student\App\User\User;
use Student\App\User\UUID;
use Student\App\Post\Post;
use Student\App\Repo\UserRepo\SQLUserRepo;
use Student\App\Repo\PostRepo\SQLPostRepo;
use Student\App\Repo\CommentRepo\SQLCommentRepo;
use Student\App\Commands\CreateUserCommand;
use Student\App\Commands\CreatePostCommand;
use Student\App\Commands\CreateCommentCommand;
use Student\App\Commands\UserArguments;
use Student\App\Commands\PostArguments;
use Student\App\Commands\CommentArguments;
use Student\App\Exceptions\CommandException;
use Student\App\Exceptions\UserNotFoundException;
use Student\App\Exceptions\PostNotFoundException;
use Psr\Log\LoggerInterface;

// $connection = require 'db.php';

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

switch ($argv[1]) {
    case 'user':
        // $userRepository = new SQLUserRepo($connection);

        // $command = new CreateUserCommand($userRepository);

        $command = $container->get(CreateUserCommand::class);

        try {
            $command->handle(UserArguments::parseRawInput($argv));
        } catch (CommandException $e) {
            // echo "{$e->getMessage()}\n";
            $logger->error($e->getMessage(), ['exception' => $e]);
        }
        break;

    case 'post':
        // $userRepository = new SQLUserRepo($connection);

        $userRepository = $container->get(SQLUserRepo::class);

        try {
            $user = $userRepository->getByUsername($argv[2]);
        } catch (UserNotFoundException $e) {
            echo "{$e->getMessage()}\n";
            break;
        }

        $id = (string) $user->getIdtitle();

        $argv[2] = $id;

        // $postRepository = new SQLPostRepo($connection);

        // $command = new CreatePostCommand($postRepository);

        $command = $container->get(CreatePostCommand::class);

        try {
            $command->handle(PostArguments::parseRawInput($argv));
        } catch (CommandException $e) {
            echo "{$e->getMessage()}\n";
        }
        break;

    case 'comment':
        // $userRepository = new SQLUserRepo($connection);

        $userRepository = $container->get(SQLUserRepo::class);

        try {
            $user = $userRepository->getByUsername($argv[2]);
        } catch (UserNotFoundException $e) {
            echo "{$e->getMessage()}\n";
            break;
        }

        $id = (string) $user->getId();

        $argv[2] = $id;

        // $postRepository = new SQLPostRepo($connection);

        $postRepository = $container->get(SQLPostRepo::class);

        try {
            $post = $postRepository->getPost(new UUID($argv[3]));
        } catch (PostNotFoundException $e) {
            echo "{$e->getMessage()}\n";
            break;
        }

        // $commentRepository = new SQLCommentRepo($connection);

        // $command = new CreateCommentCommand($commentRepository);

        $command = $container->get(CreateCommentCommand::class);

        try {
            $command->handle(CommentArguments::parseRawInput($argv));
        } catch (CommandException $e) {
            echo "{$e->getMessage()}\n";
        }

        break;
    case 'test':

        var_dump($_SERVER['SQLITE_DB_PATH']);

        break;
};