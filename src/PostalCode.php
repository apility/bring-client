<?php

namespace Apility\Bring;

use Apility\Bring\Client;
use Apility\Bring\Facades\Bring;

class PostalCode
{
    /** @var string */
    public $city;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $type;

    const NORMAL = 'NORMAL';
    const POBOX = 'POBOX';
    const SPECIALCUSTOMER = 'SPECIALCUSTOMER';
    const SPECIALNOSTREET = 'SPECIALNOSTREET';
    const UNKNOWN = 'UNKOWN';

    public function __construct($postalCode)
    {
        $this->city = $postalCode->city;
        $this->postalCode = $postalCode->postalcode;
        $this->type = $postalCode->postalCodeType;
    }

    public function __toString()
    {
        return $this->postalCode . ', ' . $this->city;
    }

    public static function find($postalCode, $countryCode = Client::NORWAY)
    {
        if ($postalCode = Bring::lookupPostalCode($postalCode, $countryCode)) {
            return $postalCode;
        }
    }

    public static function findOrFail($postalCode, $countryCode = Client::NORWAY)
    {
        return Bring::lookupPostalCodeOrFail($postalCode, $countryCode);
    }
}
