<?php

namespace Student\App\User;

class UUID
{
    private string $uuidString;

    public function __construct(string $uuidString) 
    {
        $this->uuidString = $uuidString;
    }

    public static function random(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }

    public function __toString(): string 
    {
        return $this->uuidString;
    }

}