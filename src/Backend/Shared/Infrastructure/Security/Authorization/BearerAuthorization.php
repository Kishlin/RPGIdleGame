<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Security\Authorization;

final class BearerAuthorization
{
    private function __construct(
        private string $token,
    ) {
    }

    public static function fromHeader(string $header): self
    {
        sscanf($header, 'Bearer %s', $token);

        if (null === $token) {
            throw new FailedToDecodeHeaderException('Failed to read the token.');
        }

        return new self($token);
    }

    public function token(): string
    {
        return $this->token;
    }
}
