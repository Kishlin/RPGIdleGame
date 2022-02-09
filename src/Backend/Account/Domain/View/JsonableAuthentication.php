<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableAuthentication extends JsonableView
{
    private string $token;
    private string $refreshToken;

    public function toArray(): array
    {
        return [
            'token'        => $this->token,
            'refreshToken' => $this->refreshToken,
        ];
    }

    public static function fromScalars(string $token, string $refreshToken): self
    {
        $view = new self();

        $view->token        = $token;
        $view->refreshToken = $refreshToken;

        return $view;
    }
}
