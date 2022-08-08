<?php

require_once __DIR__ . '/vendor/autoload.php';

use Student\App\User\User;
use Student\App\Post\Post;
use Student\App\Post\Comment;

$faker = Faker\Factory::create('ru_RU');

switch ($argv[1]) {
    case 'user':
        $user = new User($faker->lastName, $faker->firstName);
        echo $user->getName() . "\n";
        break;
    case 'post':
        $user = new User($faker->lastName, $faker->firstName);
        $post = new Post($user, $faker->text, $faker->text);
        echo $post->getData() . "\n";
        break;
    case 'comment':
        $user = new User($faker->lastName, $faker->firstName);
        $post = new Post($user, $faker->text, $faker->text);
        $comment = new Comment($user, $post, $faker->text);
        echo $comment->getText() . "\n";
        break;
};