<?php

namespace Apility\Bring\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed lookupPostalCode(string|int $postalCode, string $countryCode = 'NO')
 * @method static mixed lookupPostalCodeOrFail(string|int $postalCode, string $countryCode = 'NO')
 */
class Bring extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bring.client';
    }
}
