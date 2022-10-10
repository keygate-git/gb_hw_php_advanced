<?php

namespace Student\App\Repo\LikeRepo;

use Student\App\Post\Like;
use Student\App\User\UUID;



interface LikeRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $uuid): array;
}

