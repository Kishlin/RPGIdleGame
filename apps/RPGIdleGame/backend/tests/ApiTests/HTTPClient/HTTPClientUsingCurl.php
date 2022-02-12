<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient;

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

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new Response($httpCode, $output);
    }

    public function post(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->params()));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new Response($httpCode, $output);
    }

    public function update(Request $request): Response
    {
        throw new \RuntimeException('Not implemented.');
    }

    public function delete(Request $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->host}{$request->uri()}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers());

        $output = (string) curl_exec($ch);

        /** @var int $httpCode */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new Response($httpCode, $output);
    }
}
