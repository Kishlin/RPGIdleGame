<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\View;

final class SimpleAuthenticationDTO
{
    private string $token;

    public function token(): string
    {
        return $this->token;
    }

    public static function fromScalars(string $token): self
    {
        $view = new self();

        $view->token = $token;

        return $view;
    }
}
