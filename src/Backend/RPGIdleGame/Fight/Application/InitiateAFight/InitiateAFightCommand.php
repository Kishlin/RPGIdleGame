<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class InitiateAFightCommand implements Command
{
    private function __construct(
        private string $fighterId,
        private string $requesterId,
    ) {
    }

    public function fighterId(): FighterId
    {
        return new FighterId($this->fighterId);
    }

    public function requesterId(): FightRequesterId
    {
        return new FightRequesterId($this->requesterId);
    }

    public static function fromScalars(string $fighterId, string $requesterId): self
    {
        return new self($fighterId, $requesterId);
    }
}
