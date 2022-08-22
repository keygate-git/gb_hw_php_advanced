<?php

namespace Student\App\User;

use Student\App\User\UUID;

class User {
    private UUID $id;
    private string $username;
    private string $lastName;
    private string $firstName;

    public function __construct(UUID $id, string $username, string $lastName, string $firstName)
    {
        $this->id = $id;
        $this->username = $username;;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }

    public function getUsername(): string
    {
        $username = $this->username;
        return $username;
    }

    public function getFirstName(): string
    {
        $firstName = $this->firstName;
        return $firstName;
    }

    public function getLastName(): string
    {
        $lastName = $this->lastName;
        return $lastName;
    }

    public function getId(): UUID
    {
        $id = $this->id;
        return $id;
    }

}