<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class DistributeSkillPointsCommand implements Command
{
    private function __construct(
        private string $characterId,
        private int $healthPointsToAdd,
        private int $attackPointsToAdd,
        private int $defensePointsToAdd,
        private int $magikPointsToAdd,
    ) {
    }

    public function characterId(): CharacterId
    {
        return new CharacterId($this->characterId);
    }

    public function healthPointsToAdd(): int
    {
        return $this->healthPointsToAdd;
    }

    public function attackPointsToAdd(): int
    {
        return $this->attackPointsToAdd;
    }

    public function defensePointsToAdd(): int
    {
        return $this->defensePointsToAdd;
    }

    public function magikPointsToAdd(): int
    {
        return $this->magikPointsToAdd;
    }

    public static function fromScalars(
        string $characterId,
        int $healthPointsToAdd,
        int $attackPointsToAdd,
        int $defensePointsToAdd,
        int $magikPointsToAdd,
    ): self {
        return new self($characterId, $healthPointsToAdd, $attackPointsToAdd, $defensePointsToAdd, $magikPointsToAdd);
    }
}
