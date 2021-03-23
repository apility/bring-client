<?php

namespace Apility\Bring;

use Throwable;

use GuzzleHttp\Exception\ClientException;
use Apility\Bring\Exceptions\AuthenticationRequired;
use Apility\Bring\Exceptions\BringException;
use Apility\Bring\Exceptions\NonExistingPostalCode;
use Apility\Bring\Exceptions\UnsupportedCountryCode;

use Netflex\API\Client as APIClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

use Illuminate\Support\Str;

class Client extends APIClient
{
    const NORWAY = 'NO';
    const DENMARK = 'DK';
    const SWEDEN = 'SE';
    const FINLAND = 'FI';
    const NETHERLANDS = 'NL';
    const GERMANY = 'DE';
    const UNITED_STATES = 'US';
    const BELGIUM = 'BE';
    const FAROE_ISLANDS = 'FO';
    const GREENLAND = 'GL';

    const E_ERROR_CODE = 'errorCode';
    const E_NON_EXISTING_POSTAL_CODE = 'NON_EXISTING_POSTAL_CODE';
    const E_UNSUPPORTED_COUNTRY_CODE = 'CountryCode';

    /** @var String */
    const BASE_URI = 'https://api.bring.com/pickuppoint/api/';

    public function setCredentials(array $options = [])
    {
        $options['base_uri'] = static::BASE_URI;

        if (isset($options['login_id']) && isset($options['api_key'])) {
            $options['headers'] = [
                'X-MyBring-API-Uid' => $options['login_id'],
                'X-MyBring-API-Key' => $options['api_key']
            ];

            unset($options['login_id']);
            unset($options['api_key']);
        }

        $stack = HandlerStack::create(new CurlHandler());

        $stack->push(bring_json_middleware());
        $stack->push(bring_client_url_middleware());

        $options['handler'] = $stack;

        $this->client = new GuzzleClient($options);

        return $this->client;
    }

    public function lookupPostalCodeOrFail($postalCode, $countryCode = Client::NORWAY)
    {
        if (is_null($postalCode)) {
            throw new NonExistingPostalCode();
        }

        if (is_null($countryCode)) {
            throw new UnsupportedCountryCode(json_encode(null));
        }

        try {
            if ($postalCode = $this->get("postalCode/$countryCode/getCityAndType/$postalCode")->postalCode) {
                return new PostalCode($postalCode);
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $status = $response->getStatusCode();

            switch ($status) {
                case 400:
                    $type = $response->getHeaderLine('Content-Type');
                    if ($type === 'application/json') {
                        $error = json_decode($response->getBody());

                        if (isset($error->error) && is_array($error->error)) {
                            foreach ($error->error as $error) {
                                switch ($error->parameter) {
                                    case static::E_ERROR_CODE:
                                        switch ($error->error) {
                                            case static::E_NON_EXISTING_POSTAL_CODE:
                                                throw new NonExistingPostalCode($postalCode);
                                        }
                                        break;
                                    case static::E_UNSUPPORTED_COUNTRY_CODE:
                                        throw new UnsupportedCountryCode($countryCode);
                                }
                            }
                        }
                    }

                    $body = (string) $response->getBody();
                    if (Str::contains($body, 'Authentication required')) {
                        throw new AuthenticationRequired;
                    }
                default:
                    throw new BringException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    public function lookupPostalCode($postalCode, $countryCode = Client::NORWAY)
    {
        try {
            return $this->lookupPostalCodeOrFail($postalCode, $countryCode);
        } catch (Throwable $e) {
            return null;
        }
    }
}
