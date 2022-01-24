<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use DateTimeImmutable;
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

final class Character extends AggregateRoot
{
    private ?CharacterRestingUntil $characterRestingUntil = null;

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
        );

        $character->record(new CharacterCreatedDomainEvent($characterId, $characterOwner));

        return $character;
    }

    public function wonAFight(): void
    {
        $this->characterFightsCount->tookPartInAFight();
        $this->characterSkillPoint->earnASkillPoint();
        $this->characterRank->rankUp();

        $this->characterRestingUntil = null;
    }

    public function lostAFight(DateTimeImmutable $hasToRestUntil): void
    {
        $this->characterFightsCount->tookPartInAFight();
        $this->characterRank->rankDownIfItCan();

        $this->characterRestingUntil = new CharacterRestingUntil($hasToRestUntil);
    }

    public function characterId(): CharacterId
    {
        return $this->characterId;
    }
}