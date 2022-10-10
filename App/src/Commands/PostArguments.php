<?php

namespace Student\App\Commands;

use Student\App\Exceptions\CommandException;

class PostArguments
{
    public static function parseRawInput(array $rawInput): array 
    {
        $input = [];

        if (null == $rawInput[4]) {
            throw new CommandException("Too few arguments");
        }
        
        $input['author_id'] = $rawInput[2];
        $input['title'] = $rawInput[3];
        $input['text'] = $rawInput[4];

        return $input;

    }
}