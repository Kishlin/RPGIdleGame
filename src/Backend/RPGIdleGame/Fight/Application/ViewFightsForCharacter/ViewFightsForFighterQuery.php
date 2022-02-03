<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewFightsForFighterQuery implements Query
{
    private function __construct(
        private string $fighterId,
        private string $requesterId,
    ) {
    }

    public function fighterId(): string
    {
        return $this->fighterId;
    }

    public function requesterId(): string
    {
        return $this->requesterId;
    }

    public static function fromScalars(string $fighterId, string $requesterId): self
    {
        return new self($fighterId, $requesterId);
    }
}
