<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

final class AuthenticationDTO
{
    private string $token;
    private string $refreshToken;

    public function token(): string
    {
        return $this->token;
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    public static function fromScalars(string $token, string $refreshToken): self
    {
        $view = new self();

        $view->token        = $token;
        $view->refreshToken = $refreshToken;

        return $view;
    }
}
