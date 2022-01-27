<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewCharacterQuery implements Query
{
    private function __construct(
        private string $characterId,
    ) {
    }

    public function characterId(): string
    {
        return $this->characterId;
    }

    public static function fromScalars(string $characterId): self
    {
        return new self($characterId);
    }
}
