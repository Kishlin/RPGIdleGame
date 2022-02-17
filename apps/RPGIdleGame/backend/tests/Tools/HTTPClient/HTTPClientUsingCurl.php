<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient;

final class HTTPClientUsingCurl implements HTTPClientInterface
{
    public function __construct(
        private string $host,
    ) {
    }

    public function get(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIELIST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /** @var string[] $cookies */
        $cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);

        curl_close($ch);

        return new Response($httpCode, $output, $cookies);
    }

    public function post(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIELIST, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->params()));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /** @var string[] $cookies */
        $cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);

        curl_close($ch);

        return new Response($httpCode, $output, $cookies);
    }

    public function put(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIELIST, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->params()));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /** @var string[] $cookies */
        $cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);

        curl_close($ch);

        return new Response($httpCode, $output, $cookies);
    }

    public function delete(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIELIST, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /** @var string[] $cookies */
        $cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);

        curl_close($ch);

        return new Response($httpCode, $output, $cookies);
    }
}
