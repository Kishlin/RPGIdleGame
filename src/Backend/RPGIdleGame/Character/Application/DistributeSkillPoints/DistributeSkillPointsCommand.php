<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Message\Mapping;

final class DistributeSkillPointsCommand implements Command
{
    use Mapping;

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

    /**
     * @param array{characterId: string, requesterId: string, health: int, attack: int, defense: int, magik: int} $source
     */
    public static function fromRequest(array $source): self
    {
        return new self(
            self::getString($source, 'characterId'),
            self::getString($source, 'requesterId'),
            self::getInt($source, 'health'),
            self::getInt($source, 'attack'),
            self::getInt($source, 'defense'),
            self::getInt($source, 'magik'),
        );
    }
}
