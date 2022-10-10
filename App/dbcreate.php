<?php

$connection = require 'db.php';

$query = 'DROP TABLE users';

$connection->exec($query);

$query = 'CREATE TABLE users (
    id TEXT NOT NULL PRIMARY KEY,
    username TEXT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL
)';

$connection->exec($query);

$query = 'CREATE TABLE posts (
    id TEXT NOT NULL PRIMARY KEY,
    author_id TEXT NOT NULL,
    title TEXT NOT NULL,
    text TEXT NOT NULL
)';

$connection->exec($query);

$query = 'CREATE TABLE comments (
    id TEXT NOT NULL PRIMARY KEY,
    author_id TEXT NOT NULL,
    post_id TEXT NOT NULL,
    text TEXT NOT NULL
)';

$connection->exec($query);

$query = 'CREATE TABLE likes (
    id TEXT NOT NULL PRIMARY KEY,
    author_id TEXT NOT NULL,
    post_id TEXT NOT NULL
)';

$connection->exec($query);