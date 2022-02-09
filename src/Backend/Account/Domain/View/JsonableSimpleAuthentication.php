<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableSimpleAuthentication extends JsonableView
{
    private string $token;

    public function toArray(): array
    {
        return [
            'token' => $this->token,
        ];
    }

    public static function fromScalars(string $token): self
    {
        $view = new self();

        $view->token = $token;

        return $view;
    }
}
