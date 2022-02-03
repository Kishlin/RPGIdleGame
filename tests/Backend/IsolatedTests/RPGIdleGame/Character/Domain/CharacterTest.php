<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\Character
 */
final class CharacterTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $characterId    = new CharacterId('413893f0-a041-430a-95c0-70f10aff8de6');
        $characterOwner = new CharacterOwner('d71ddce2-ca04-4066-abb0-189f481b5ac9');
        $characterName  = new CharacterName('Kishlin');

        $character = Character::createFresh($characterId, $characterName, $characterOwner);

        self::assertItRecordedDomainEvents(
            $character,
            new CharacterCreatedDomainEvent($characterId, $characterOwner),
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testAddingOneHealthPointCostsOneSkillPoint(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 100, healthPoints: 5);

        $character->increaseHealthBy(1);

        self::assertSame(6 /* 5 + 1 */, $character->health()->value());
        self::assertSame(99 /* 100 - 1 */, $character->skillPoint()->value());

        $character->increaseHealthBy(60);

        self::assertSame(66 /* 6 + 60 */, $character->health()->value());
        self::assertSame(39 /* 99 - 60 */, $character->skillPoint()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testTheFirstAttackPointCostsOneSkillPoint(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 10, attackPoints: 0);

        $character->increaseAttackBy(1);

        self::assertSame(1, $character->attack()->value());
        self::assertSame(9 /* 10 - 1 */, $character->skillPoint()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testAttackPointsCostOneFifthOfTheCurrentLevelRoundedUp(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 100, attackPoints: 10);

        $character->increaseAttackBy(1);

        self::assertSame(11, $character->attack()->value());
        self::assertSame(98 /* 100 - 2 (10 / 5) */, $character->skillPoint()->value());

        $character->increaseAttackBy(1);

        self::assertSame(12, $character->attack()->value());
        self::assertSame(95 /* 98 - 3 (11 / 5 rounded up) */, $character->skillPoint()->value());

        $character->increaseAttackBy(20);

        self::assertSame(32, $character->attack()->value());
        self::assertSame(1 /* 95 - 4*3 - 5*4 - 5*5 - 5*6 - 1*7 */, $character->skillPoint()->value());

        self::expectException(NotEnoughSkillPointsException::class);
        $character->increaseAttackBy(1); // Would cost 7, but it has 1 skill point
    }

    /**
     * @throws ReflectionException
     */
    public function testTheFirstDefensePointCostsOneSkillPoint(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 10, defensePoints: 0);

        $character->increaseDefenseBy(1);

        self::assertSame(1, $character->defense()->value());
        self::assertSame(9 /* 10 - 1 */, $character->skillPoint()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testDefensePointsCostOneFifthOfTheCurrentLevelRoundedUp(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 100, defensePoints: 10);

        $character->increaseDefenseBy(1);

        self::assertSame(11, $character->defense()->value());
        self::assertSame(98 /* 100 - 2 (10 / 5) */, $character->skillPoint()->value());

        $character->increaseDefenseBy(1);

        self::assertSame(12, $character->defense()->value());
        self::assertSame(95 /* 98 - 3 (11 / 5 rounded up) */, $character->skillPoint()->value());

        $character->increaseDefenseBy(20);

        self::assertSame(32, $character->defense()->value());
        self::assertSame(1 /* 95 - 4*3 - 5*4 - 5*5 - 5*6 - 1*7 */, $character->skillPoint()->value());

        self::expectException(NotEnoughSkillPointsException::class);
        $character->increaseDefenseBy(1); // Would cost 7, but it has 1 skill point
    }

    /**
     * @throws ReflectionException
     */
    public function testTheFirstMagikPointCostsOneSkillPoint(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 10, magikPoints: 0);

        $character->increaseMagikBy(1);

        self::assertSame(1, $character->magik()->value());
        self::assertSame(9 /* 10 - 1 */, $character->skillPoint()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testMagikPointsCostOneFifthOfTheCurrentLevelRoundedUp(): void
    {
        $character = CharacterProvider::tweakedCharacter(skillPoints: 100, magikPoints: 10);

        $character->increaseMagikBy(1);

        self::assertSame(11, $character->magik()->value());
        self::assertSame(98 /* 100 - 2 (10 / 5) */, $character->skillPoint()->value());

        $character->increaseMagikBy(1);

        self::assertSame(12, $character->magik()->value());
        self::assertSame(95 /* 98 - 3 (11 / 5 rounded up) */, $character->skillPoint()->value());

        $character->increaseMagikBy(20);

        self::assertSame(32, $character->magik()->value());
        self::assertSame(1 /* 95 - 4*3 - 5*4 - 5*5 - 5*6 - 1*7 */, $character->skillPoint()->value());

        self::expectException(NotEnoughSkillPointsException::class);
        $character->increaseMagikBy(1); // Would cost 7, but it has 1 skill point
    }
}
