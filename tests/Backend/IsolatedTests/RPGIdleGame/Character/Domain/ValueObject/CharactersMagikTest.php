<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik
 */
final class CharactersMagikTest extends TestCase
{
    public function testItCanAddMagikPoints(): void
    {
        $magik = new CharacterMagik(10);

        self::assertSame(15, $magik->addMagikPoints(5)->value());
    }

    public function testItCannotRemovePoints(): void
    {
        $magik = new CharacterMagik(10);

        self::expectException(PointsCanOnlyBeIncreasedException::class);
        $magik->addMagikPoints(-5);
    }
}
