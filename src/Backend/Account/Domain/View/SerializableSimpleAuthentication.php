<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableSimpleAuthentication extends SerializableView
{
    private string $token;

    /**
     * @return array{token: string}
     */
    public function __serialize(): array
    {
        return [
            'token' => $this->token,
        ];
    }

    /**
     * @param array{token: string} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'token' => $this->token,
        ] = $data;
    }

    public static function fromScalars(string $token): self
    {
        $view = new self();

        $view->token = $token;

        return $view;
    }
}
