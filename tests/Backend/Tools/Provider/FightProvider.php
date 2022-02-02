<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

final class FightProvider
{
    public static function initiatedFight(): Fight
    {
        return Fight::initiate(
            new FightId('5c0efde2-135d-4118-98d3-0ca073577551'),
            FightParticipantProvider::fightInitiatorWithValues('a34f3829-b9bc-48c5-9fe5-7d58fe5f3db7', 25, 5, 1, 4, 17),
            FightParticipantProvider::fightOpponentWithValues('8cc8142a-d055-4e6d-b9dc-f2e06c967f93', 21, 4, 3, 1, 15),
        );
    }

    public static function fightWhereParticipantsCannotDamageEachOther(): Fight
    {
        return Fight::initiate(
            new FightId('f098058d-c568-4c58-8ff2-47d0dfc339ae'),
            FightParticipantProvider::fightInitiatorWithValues('127c8abd-7b0f-4e50-b315-5bbee38bd0e1', 25, 5, 5, 4, 17),
            FightParticipantProvider::fightOpponentWithValues('a0b5c4da-4814-4dc6-ab19-d6584f240410', 21, 4, 9, 1, 15),
        );
    }

    public static function fightWhereOnlyInitiatorCanDealDamages(): Fight
    {
        return Fight::initiate(
            new FightId('6dab89d9-0784-431b-a41e-83a682d6232e'),
            FightParticipantProvider::fightInitiatorWithValues('4611b1eb-282a-4863-80d5-6e51a3e8f765', 25, 5, 5, 4, 17),
            FightParticipantProvider::fightOpponentWithValues('c8d23fa7-ecdc-48fd-8d19-dd2ba7e37245', 21, 4, 3, 1, 15),
        );
    }

    public static function fightWhereOnlyOpponentCanDealDamages(): Fight
    {
        return Fight::initiate(
            new FightId('f098058d-c568-4c58-8ff2-47d0dfc339ae'),
            FightParticipantProvider::fightInitiatorWithValues('127c8abd-7b0f-4e50-b315-5bbee38bd0e1', 25, 5, 1, 4, 17),
            FightParticipantProvider::fightOpponentWithValues('a0b5c4da-4814-4dc6-ab19-d6584f240410', 21, 4, 9, 1, 15),
        );
    }

    /**
     * @throws ReflectionException
     */
    public static function fightWithThreeTurns(): Fight
    {
        $fight = self::initiatedFight();

        ReflectionHelper::writePropertyValue($fight, 'turns', [
            FightTurn::create(
                $fight,
                new FightTurnId('71812ad7-39d6-4f1c-912e-0721d6456f38'),
                FightParticipantProvider::fightInitiatorWithValues('a34f3829-b9bc-48c5-9fe5-7d58fe5f3db7', 25, 5, 1, 4, 17),
                FightParticipantProvider::fightOpponentWithValues('8cc8142a-d055-4e6d-b9dc-f2e06c967f93', 19, 4, 3, 1, 15),
                new FightTurnIndex(0),
                5,
                2,
            ),
            FightTurn::create(
                $fight,
                new FightTurnId('363047cf-ca97-4209-87cf-f7bbc68a02d9'),
                FightParticipantProvider::fightOpponentWithValues('8cc8142a-d055-4e6d-b9dc-f2e06c967f93', 19, 4, 3, 1, 15),
                FightParticipantProvider::fightInitiatorWithValues('a34f3829-b9bc-48c5-9fe5-7d58fe5f3db7', 20, 5, 1, 4, 17),
                new FightTurnIndex(1),
                3,
                5,
            ),
            FightTurn::create(
                $fight,
                new FightTurnId('78797690-e82c-43ec-bc11-775537a2f072'),
                FightParticipantProvider::fightInitiatorWithValues('a34f3829-b9bc-48c5-9fe5-7d58fe5f3db7', 20, 5, 1, 4, 17),
                FightParticipantProvider::fightOpponentWithValues('8cc8142a-d055-4e6d-b9dc-f2e06c967f93', 19, 4, 3, 1, 15),
                new FightTurnIndex(2),
                1,
                0,
            ),
        ]);

        return $fight;
    }
}
