<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex;
use Kishlin\Tests\Backend\Tools\Provider\FightProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn
 */
final class FightTurnTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $fight = FightProvider::initiatedFight();

        $fightTurn = FightTurn::create(
            $fight,
            new FightTurnId('68d871e1-a2a8-4d39-b858-c722265930cf'),
            $fight->initiator(),
            $fight->opponent(),
            new FightTurnIndex(3),
            3,
            1
        );

        self::assertInstanceOf(FightTurn::class, $fightTurn);
    }
}
