<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\ReadModel;

final class AccountDetailsForAuthentication
{
    private function __construct(
        private string $id,
        private string $passwordHash,
        private string $salt,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function salt(): string
    {
        return $this->salt;
    }

    public static function fromScalars(string $userId, string $passwordHash, string $salt): self
    {
        return new self($userId, $passwordHash, $salt);
    }
}
