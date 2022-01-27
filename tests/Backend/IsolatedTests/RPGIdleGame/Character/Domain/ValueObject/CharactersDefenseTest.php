<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense
 */
final class CharactersDefenseTest extends TestCase
{
    public function testItCanAddMagikPoints(): void
    {
        $magik = new CharacterDefense(10);

        self::assertSame(15, $magik->addDefensePoints(5)->value());
    }

    public function testItCannotRemovePoints(): void
    {
        $magik = new CharacterDefense(10);

        self::expectException(PointsCanOnlyBeIncreasedException::class);
        $magik->addDefensePoints(-5);
    }
}
