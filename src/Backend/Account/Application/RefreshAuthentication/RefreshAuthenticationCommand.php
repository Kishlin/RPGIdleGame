<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class RefreshAuthenticationCommand implements Command
{
    private function __construct(
        private string $refreshToken,
    ) {
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    public static function fromScalars(string $refreshToken): self
    {
        return new self($refreshToken);
    }
}
