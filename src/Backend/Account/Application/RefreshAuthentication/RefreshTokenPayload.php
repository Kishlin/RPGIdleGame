<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

final class RefreshTokenPayload
{
    public function __construct(
        private string $userId,
        private string $salt,
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function salt(): string
    {
        return $this->salt;
    }

    public static function fromScalars(string $userId, string $salt): self
    {
        return new self($userId, $salt);
    }
}
