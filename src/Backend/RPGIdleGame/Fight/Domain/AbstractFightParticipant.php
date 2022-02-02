<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantCharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

abstract class AbstractFightParticipant
{
    final private function __construct(
        protected FightParticipantId $id,
        protected FightParticipantCharacterId $characterId,
        protected FightParticipantHealth $health,
        protected FightParticipantAttack $attack,
        protected FightParticipantDefense $defense,
        protected FightParticipantMagik $magik,
        protected FightParticipantRank $rank,
    ) {
    }

    public static function create(
        UuidValueObject $characterId,
        FightParticipantId $id,
        FightParticipantHealth $health,
        FightParticipantAttack $attack,
        FightParticipantDefense $defense,
        FightParticipantMagik $magik,
        FightParticipantRank $rank,
    ): static {
        if (false === $characterId instanceof FightParticipantCharacterId) {
            $characterId = FightParticipantCharacterId::fromOther($characterId);
        }

        return new static(
            $id,
            $characterId,
            $health,
            $attack,
            $defense,
            $magik,
            $rank,
        );
    }

    public function clone(): self
    {
        return new static(
            clone $this->id,
            clone $this->characterId,
            clone $this->health,
            clone $this->attack,
            clone $this->defense,
            clone $this->magik,
            clone $this->rank,
        );
    }

    public function totalAttackValue(int $diceRoll): int
    {
        if ($this->diceRollMatchesMagik($diceRoll)) {
            return $this->addMagikToDiceRoll($diceRoll);
        }

        return $diceRoll;
    }

    /**
     * @return int Total damage dealt, after defenses are applied
     */
    public function dealDamages(int $attackValue): int
    {
        $actualDamages = $attackValue - $this->defense->value();
        $this->health  = $this->health->removeHealth($actualDamages);

        return max(0, $actualDamages);
    }

    public function isStillAlive(): bool
    {
        return $this->health->isStillAlive();
    }

    public function id(): FightParticipantId
    {
        return $this->id;
    }

    public function characterId(): FightParticipantCharacterId
    {
        return $this->characterId;
    }

    public function health(): FightParticipantHealth
    {
        return $this->health;
    }

    public function attack(): FightParticipantAttack
    {
        return $this->attack;
    }

    public function defense(): FightParticipantDefense
    {
        return $this->defense;
    }

    public function magik(): FightParticipantMagik
    {
        return $this->magik;
    }

    public function diceRollMatchesMagik(int $diceRoll): bool
    {
        return $this->magik->value() === $diceRoll;
    }

    private function addMagikToDiceRoll(int $diceRoll): int
    {
        return $diceRoll + $this->magik->value();
    }
}
