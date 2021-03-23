<?php

namespace Apility\Bring\Exceptions;

use Apility\Bring\Exceptions\BringException;

class UnsupportedCountryCode extends BringException
{
    public function __construct($countryCode)
    {
        parent::__construct('Country code "' . $countryCode . '" not supported');
    }
}
