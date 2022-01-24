<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Security\Authorization;

final class BasicAuthorization
{
    private function __construct(
        private string $username,
        private string $password,
    ) {
    }

    public static function fromHeader(string $header): self
    {
        sscanf($header, 'Basic %s', $encodedCredentials);

        if (null === $encodedCredentials) {
            throw new FailedToDecodeHeaderException('Failed to read encoded credentials.');
        }

        $decodedCredentials = base64_decode($encodedCredentials, true);

        if (false === $decodedCredentials) {
            throw new FailedToDecodeHeaderException('Failed to decode credentials.');
        }

        $data = explode(':', $decodedCredentials);

        if (2 !== count($data)) {
            throw new FailedToDecodeHeaderException('The decoded string did not meet the expected format.');
        }

        return new self(...$data);
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }
}
