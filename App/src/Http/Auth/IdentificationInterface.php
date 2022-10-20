<?php

namespace Student\App\Http\Auth;

use Student\App\Http\Request;
use Student\App\User\User;

interface IdentificationInterface
{
    public function user(Request $request): User;

}