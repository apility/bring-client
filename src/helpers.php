<?php

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Psr\Http\Message\RequestInterface;

if (!function_exists('bring_json_middleware')) {
    function bring_json_middleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $uri = $request->getUri()->__toString();

                if (!Str::endsWith($uri, '.json')) {
                    $uri = new Uri($uri . '.json');
                    $request = $request->withUri($uri);
                }

                return $handler($request, $options);
            };
        };
    }
}

if (!function_exists('bring_client_url_middleware')) {
    function bring_client_url_middleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (!(strpos(Request::getHost(), 'localhost') === 0)) {
                    $request = $request->withHeader('X-Bring-Client-URL	', Request::url());
                }

                return $handler($request, $options);
            };
        };
    }
}
