# Bring API Client for the Netflex SDK

## Setup

Add `config/bring.php` like the following example:

```php
return [
    'login_id' => 'user@example.com',
    'api_key' => '00000000-000-0000-0000-000000000000'
];
```

or simply create the following .ENV variables:

```
BRING_LOGIN_ID=user@example.com
BRING_API_KEY=00000000-000-0000-0000-000000000000
```

## Usage

```php
<?php

use Bring;

$postalCode = Bring::lookupPostalCode(5161);

if ($postalCode) {
    echo $postalCode->city;
} else {
    echo 'Postalcode not found';
}
```