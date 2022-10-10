<?php

namespace Student\App\Commands;

use Student\App\Exceptions\CommandException;

class UserArguments
{
    public static function parseRawInput(array $rawInput): array 
    {
        $input = [];

        if (null == $rawInput[4]) {
            throw new CommandException("Too few arguments");
        }
        
        $input['username'] = $rawInput[2];
        $input['last_name'] = $rawInput[3];
        $input['first_name'] = $rawInput[4];

        return $input;

    }
}