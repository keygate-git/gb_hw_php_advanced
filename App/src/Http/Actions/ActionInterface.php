<?php

namespace Student\App\Http\Actions;

use Student\App\Http\Request;
use Student\App\Http\Response;

interface ActionInterface 
{
    public function handle(Request $request): Response;
}