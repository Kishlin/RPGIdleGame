<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain;

use Kishlin\Tests\Backend\Tools\Provider\FightParticipantProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent
 */
final class AbstractFightParticipantTest extends TestCase
{
    public function testAttackValueEqualsTheDiceRoll(): void
    {
        $attacker = FightParticipantProvider::fightParticipant();

        $diceRoll    = 5; // Not equal to attacker's magik
        $attackValue = $attacker->totalAttackValue($diceRoll);

        self::assertSame($diceRoll, $attackValue);
    }

    public function testMagikIsAddedToAttackValueIfItEqualsTheDiceRoll(): void
    {
        $attacker = FightParticipantProvider::fightParticipant();

        $diceRoll    = $attacker->magik()->value();
        $attackValue = $attacker->totalAttackValue($diceRoll);

        self::assertSame($diceRoll + $attacker->magik()->value(), $attackValue);
    }

    public function testDefenseIsDeductedFromTheAttackValue(): void
    {
        $defender = FightParticipantProvider::fightParticipant();

        $attackValue  = 15;
        $totalDamages = $defender->dealDamages($attackValue);

        self::assertSame($attackValue - $defender->defense()->value(), $totalDamages);
    }

    public function testDamageDealtAreZeroWhenDefenseIsTooHigh(): void
    {
        $defender = FightParticipantProvider::fightParticipant();

        $insufficientAttack = $defender->defense()->value() - 1;
        $totalDamages       = $defender->dealDamages($insufficientAttack);

        self::assertSame(0, $totalDamages);
    }
}
