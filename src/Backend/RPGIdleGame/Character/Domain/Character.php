<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterFightsCount;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRestingUntil;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

final class Character extends AggregateRoot
{
    private function __construct(
        private CharacterId $characterId,
        private CharacterName $characterName,
        private CharacterOwner $characterOwner,
        private CharacterSkillPoint $characterSkillPoint,
        private CharacterHealth $characterHealth,
        private CharacterAttack $characterAttack,
        private CharacterDefense $characterDefense,
        private CharacterMagik $characterMagik,
        private CharacterRank $characterRank,
        private CharacterFightsCount $characterFightsCount,
        private ?CharacterRestingUntil $characterRestingUntil,
    ) {
    }

    public static function createFresh(
        CharacterId $characterId,
        CharacterName $characterName,
        CharacterOwner $characterOwner,
    ): self {
        $character = new self(
            $characterId,
            $characterName,
            $characterOwner,
            new CharacterSkillPoint(12),
            new CharacterHealth(10),
            new CharacterAttack(0),
            new CharacterDefense(0),
            new CharacterMagik(0),
            new CharacterRank(1),
            new CharacterFightsCount(0),
            null,
        );

        $character->record(new CharacterCreatedDomainEvent($characterId, $characterOwner));

        return $character;
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseHealthBy(int $healthPointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($healthPointsToAdd);

        if (0 === $healthPointsToAdd) {
            return;
        }

        $costInSkillPoints = $healthPointsToAdd;

        $this->characterSkillPoint = $this->characterSkillPoint->removeSkillPoints($costInSkillPoints);
        $this->characterHealth     = $this->characterHealth->addHealthPoints($healthPointsToAdd);
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseAttackBy(int $attackPointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($attackPointsToAdd);

        for ($i = $attackPointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->characterAttack->value() ?
                (int) (ceil($this->characterAttack->value() / 5)) :
                1
            ;

            $this->characterSkillPoint = $this->characterSkillPoint->removeSkillPoints($costInSkillPoints);
            $this->characterAttack     = $this->characterAttack->addAttackPoints(1);
        }
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseDefenseBy(int $defensePointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($defensePointsToAdd);

        for ($i = $defensePointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->characterDefense->value() ?
                (int) (ceil($this->characterDefense->value() / 5)) :
                1
            ;

            $this->characterSkillPoint = $this->characterSkillPoint->removeSkillPoints($costInSkillPoints);
            $this->characterDefense    = $this->characterDefense->addDefensePoints(1);
        }
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseMagikBy(int $magikPointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($magikPointsToAdd);

        for ($i = $magikPointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->characterMagik->value() ?
                (int) (ceil($this->characterMagik->value() / 5)) :
                1
            ;

            $this->characterSkillPoint = $this->characterSkillPoint->removeSkillPoints($costInSkillPoints);
            $this->characterMagik      = $this->characterMagik->addMagikPoints(1);
        }
    }

    public function characterId(): CharacterId
    {
        return $this->characterId;
    }

    public function characterName(): CharacterName
    {
        return $this->characterName;
    }

    public function characterOwner(): CharacterOwner
    {
        return $this->characterOwner;
    }

    public function characterSkillPoint(): CharacterSkillPoint
    {
        return $this->characterSkillPoint;
    }

    public function characterHealth(): CharacterHealth
    {
        return $this->characterHealth;
    }

    public function characterAttack(): CharacterAttack
    {
        return $this->characterAttack;
    }

    public function characterDefense(): CharacterDefense
    {
        return $this->characterDefense;
    }

    public function characterMagik(): CharacterMagik
    {
        return $this->characterMagik;
    }

    /**
     * @throws PointsCanOnlyBeIncreasedException
     */
    private function refuseTheAmountIfItIsNegative(int $amount): void
    {
        if ($amount < 0) {
            throw new PointsCanOnlyBeIncreasedException();
        }
    }
}
