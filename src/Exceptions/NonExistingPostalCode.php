<?php

namespace Apility\Bring\Exceptions;

use Apility\Bring\Exceptions\BringException;

class NonExistingPostalCode extends BringException
{
    public function __construct($postalCode = null)
    {
        parent::__construct('Non existing postal code' . ($postalCode ? (' (' . $postalCode . ')') : null));
    }
}
