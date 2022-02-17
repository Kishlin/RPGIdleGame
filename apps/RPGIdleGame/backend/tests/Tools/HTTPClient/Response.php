<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient;

final class Response
{
    /** @param string[] $cookies */
    public function __construct(
        private int $httpCode,
        private string $body,
        private array $cookies,
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

    /**
     * @return string[]
     */
    public function cookies(): array
    {
        return $this->cookies;
    }

    public function decodedBody(): mixed
    {
        return json_decode($this->body, true) ?: [];
    }
}
