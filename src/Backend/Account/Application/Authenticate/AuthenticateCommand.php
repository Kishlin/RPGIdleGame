<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Authenticate;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class AuthenticateCommand implements Command
{
    private function __construct(
        private string $usernameOrEmail,
        private string $password,
    ) {
    }

    public function usernameOrEmail(): string
    {
        return $this->usernameOrEmail;
    }

    public function password(): string
    {
        return $this->password;
    }

    public static function fromScalars(string $usernameOrEmail, string $password): self
    {
        return new self($usernameOrEmail, $password);
    }
}
