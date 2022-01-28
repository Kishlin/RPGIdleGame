<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewAllCharactersQuery implements Query
{
    private function __construct(
        private string $requesterId,
    ) {
    }

    public function requesterId(): string
    {
        return $this->requesterId;
    }

    public static function fromScalars(string $requesterId): self
    {
        return new self($requesterId);
    }
}
