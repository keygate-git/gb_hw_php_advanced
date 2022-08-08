<?php

namespace Student\App\User;

class User {
    private int $id;
    private string $lastName;
    private string $firstName;

    public function __construct(string $lastName, string $firstName)
    {
        $this->id = 1;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }

    public function getName(): string
    {
        $name = $this->lastName . ' ' . $this->firstName;
        return $name;
    }

    public function getId(): int
    {
        $id = $this->id;
        return $id;
    }

}