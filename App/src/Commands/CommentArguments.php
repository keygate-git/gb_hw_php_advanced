<?php

namespace Student\App\Commands;

use Student\App\Exceptions\CommandException;

class CommentArguments
{
    public static function parseRawInput(array $rawInput): array 
    {
        $input = [];

        if (null == $rawInput[4]) {
            throw new CommandException("Too few arguments");
        }
        
        $input['author_id'] = $rawInput[2];
        $input['post_id'] = $rawInput[3];
        $input['text'] = $rawInput[4];

        return $input;

    }
}