<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableAuthentication extends SerializableView
{
    private string $token;
    private string $refreshToken;

    /**
     * @return array{token: string, refreshToken: string}
     */
    public function __serialize(): array
    {
        return [
            'token'        => $this->token,
            'refreshToken' => $this->refreshToken,
        ];
    }

    /**
     * @param array{token: string, refreshToken: string} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'token'        => $this->token,
            'refreshToken' => $this->refreshToken,
        ] = $data;
    }

    public static function fromScalars(string $token, string $refreshToken): self
    {
        $view = new self();

        $view->token        = $token;
        $view->refreshToken = $refreshToken;

        return $view;
    }
}
