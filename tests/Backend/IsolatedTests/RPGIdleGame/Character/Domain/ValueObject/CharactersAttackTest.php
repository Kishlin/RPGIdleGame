<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack
 */
final class CharactersAttackTest extends TestCase
{
    public function testItCanAddMagikPoints(): void
    {
        $magik = new CharacterAttack(10);

        self::assertSame(15, $magik->addAttackPoints(5)->value());
    }

    public function testItCannotRemovePoints(): void
    {
        $magik = new CharacterAttack(10);

        self::expectException(PointsCanOnlyBeIncreasedException::class);
        $magik->addAttackPoints(-5);
    }
}
