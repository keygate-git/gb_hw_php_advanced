<?php

namespace Student\App\Http;

use Student\App\Http\Response;

class ErrorResponse extends Response 
{
    protected const SUCCESS = false;

    private string $reason; 

    public function __construct(string $reason = 'Something goes wrong')
    {
        $this->reason = $reason;
    }

    protected function payload(): array 
    {
        return ['reason' => $this->reason];
    }
}