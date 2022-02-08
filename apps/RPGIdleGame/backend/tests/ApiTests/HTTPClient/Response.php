<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient;

final class Response
{
    public function __construct(
        private int $httpCode,
        private string $body,
    ) {
    }

    public function httpCode(): int
    {
        return $this->httpCode;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function decodedBody(): mixed
    {
        return json_decode($this->body, true) ?: [];
    }
}
