<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use DateTimeImmutable;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterActiveStatus;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAvailability;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterCreationDate;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

final class Character extends AggregateRoot
{
    private function __construct(
        private CharacterId $id,
        private CharacterName $name,
        private CharacterOwner $owner,
        private CharacterSkillPoint $skillPoint,
        private CharacterHealth $health,
        private CharacterAttack $attack,
        private CharacterDefense $defense,
        private CharacterMagik $magik,
        private CharacterRank $rank,
        private CharacterActiveStatus $activeStatus,
        private CharacterAvailability $availability,
        private CharacterCreationDate $creationDate,
    ) {
    }

    public static function createFresh(
        CharacterId $id,
        CharacterName $name,
        CharacterOwner $owner,
        DateTimeImmutable $creationDate,
    ): self {
        $character = new self(
            $id,
            $name,
            $owner,
            new CharacterSkillPoint(12),
            new CharacterHealth(10),
            new CharacterAttack(0),
            new CharacterDefense(0),
            new CharacterMagik(0),
            new CharacterRank(1),
            new CharacterActiveStatus(true),
            new CharacterAvailability($creationDate),
            new CharacterCreationDate($creationDate),
        );

        $character->record(new CharacterCreatedDomainEvent($id, $owner));

        return $character;
    }

    public function hadAFightWin(): void
    {
        $this->skillPoint = $this->skillPoint->earnASkillPoint();

        $this->rank = $this->rank->rankUp();
    }

    public function hadAFightLoss(): void
    {
        $this->rank = $this->rank->rankDownIfItCan();
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

        $this->skillPoint = $this->skillPoint->removeSkillPoints($costInSkillPoints);
        $this->health     = $this->health->addHealthPoints($healthPointsToAdd);
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseAttackBy(int $attackPointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($attackPointsToAdd);

        for ($i = $attackPointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->attack->value() ?
                (int) (ceil((1 + $this->attack->value()) / 5)) :
                1
            ;

            $this->skillPoint = $this->skillPoint->removeSkillPoints($costInSkillPoints);
            $this->attack     = $this->attack->addAttackPoints(1);
        }
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseDefenseBy(int $defensePointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($defensePointsToAdd);

        for ($i = $defensePointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->defense->value() ?
                (int) (ceil((1 + $this->defense->value()) / 5)) :
                1
            ;

            $this->skillPoint = $this->skillPoint->removeSkillPoints($costInSkillPoints);
            $this->defense    = $this->defense->addDefensePoints(1);
        }
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function increaseMagikBy(int $magikPointsToAdd): void
    {
        $this->refuseTheAmountIfItIsNegative($magikPointsToAdd);

        for ($i = $magikPointsToAdd; $i > 0; --$i) {
            $costInSkillPoints = 0 < $this->magik->value() ?
                (int) (ceil((1 + $this->magik->value()) / 5)) :
                1
            ;

            $this->skillPoint = $this->skillPoint->removeSkillPoints($costInSkillPoints);
            $this->magik      = $this->magik->addMagikPoints(1);
        }
    }

    public function softDelete(): void
    {
        $this->activeStatus = $this->activeStatus->flagAsInactive();

        $this->record(new CharacterDeletedDomainEvent($this->id, $this->owner));
    }

    public function id(): CharacterId
    {
        return $this->id;
    }

    public function name(): CharacterName
    {
        return $this->name;
    }

    public function owner(): CharacterOwner
    {
        return $this->owner;
    }

    public function skillPoint(): CharacterSkillPoint
    {
        return $this->skillPoint;
    }

    public function health(): CharacterHealth
    {
        return $this->health;
    }

    public function attack(): CharacterAttack
    {
        return $this->attack;
    }

    public function defense(): CharacterDefense
    {
        return $this->defense;
    }

    public function magik(): CharacterMagik
    {
        return $this->magik;
    }

    public function rank(): CharacterRank
    {
        return $this->rank;
    }

    public function activeStatus(): CharacterActiveStatus
    {
        return $this->activeStatus;
    }

    public function availability(): CharacterAvailability
    {
        return $this->availability;
    }

    public function creationDate(): CharacterCreationDate
    {
        return $this->creationDate;
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
