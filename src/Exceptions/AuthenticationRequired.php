<?php

namespace Apility\Bring\Exceptions;

use Apility\Bring\Exceptions\BringException;

class AuthenticationRequired extends BringException
{
    public function __construct()
    {
        parent::__construct('Authentication required');
    }
}
