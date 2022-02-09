<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Security;

final class TokenPayload
{
    private function __construct(
        private string $userId,
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public static function fromScalars(string $userId): self
    {
        return new self($userId);
    }
}
