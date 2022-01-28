<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewCharacterQuery implements Query
{
    private function __construct(
        private string $characterId,
        private string $requesterId,
    ) {
    }

    public function characterId(): string
    {
        return $this->characterId;
    }

    public function requesterId(): string
    {
        return $this->requesterId;
    }

    public static function fromScalars(string $characterId, string $requesterId): self
    {
        return new self($characterId, $requesterId);
    }
}
