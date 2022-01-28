<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class DistributeSkillPointsCommand implements Command
{
    private function __construct(
        private string $characterId,
        private string $requesterId,
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

    public function requesterId(): CharacterOwner
    {
        return new CharacterOwner($this->requesterId);
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
        string $requesterId,
        int $healthPointsToAdd,
        int $attackPointsToAdd,
        int $defensePointsToAdd,
        int $magikPointsToAdd,
    ): self {
        return new self(
            $characterId,
            $requesterId,
            $healthPointsToAdd,
            $attackPointsToAdd,
            $defensePointsToAdd,
            $magikPointsToAdd
        );
    }
}
