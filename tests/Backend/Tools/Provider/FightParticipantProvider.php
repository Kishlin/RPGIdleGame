<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class FightParticipantProvider
{
    public static function fightParticipant(): AbstractFightParticipant
    {
        return self::fightInitiator();
    }

    public static function fightInitiator(): FightInitiator
    {
        return self::fightInitiatorWithValues('92767fcc-506f-4562-ada7-846d108a38f6', 25, 6, 3, 4, 15);
    }

    public static function fightOpponentWithValues(
        string $id,
        int $health,
        int $attack,
        int $defense,
        int $magik,
        int $rank
    ): FightOpponent {
        return FightOpponent::create(
            new class('b638a804-d8ea-47de-8904-fd9728e4a7b4') extends UuidValueObject {},
            new FightParticipantId($id),
            new FightParticipantHealth($health),
            new FightParticipantAttack($attack),
            new FightParticipantDefense($defense),
            new FightParticipantMagik($magik),
            new FightParticipantRank($rank),
        );
    }

    public static function fightInitiatorWithValues(
        string $id,
        int $health,
        int $attack,
        int $defense,
        int $magik,
        int $rank
    ): FightInitiator {
        return FightInitiator::create(
            new class('ba35dec4-cc07-43b6-b399-18056b27bfbf') extends UuidValueObject {},
            new FightParticipantId($id),
            new FightParticipantHealth($health),
            new FightParticipantAttack($attack),
            new FightParticipantDefense($defense),
            new FightParticipantMagik($magik),
            new FightParticipantRank($rank),
        );
    }
}
