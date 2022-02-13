<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient;

final class Request
{
    /**
     * @param string               $uri
     * @param string[]             $headers
     * @param array<string, mixed> $params
     */
    public function __construct(
        private string $uri,
        private array $headers = [],
        private array $params = [],
    ) {
    }

    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @return string[]
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return array<string, mixed>
     */
    public function params(): array
    {
        return $this->params;
    }
}
