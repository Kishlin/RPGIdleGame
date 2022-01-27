<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CharactersHealthTest extends TestCase
{
    public function testItCanAddHealthPoints(): void
    {
        $magik = new CharacterHealth(10);

        self::assertSame(15, $magik->addHealthPoints(5)->value());
    }

    public function testItCannotRemovePoints(): void
    {
        $magik = new CharacterHealth(10);

        self::expectException(PointsCanOnlyBeIncreasedException::class);
        $magik->addHealthPoints(-5);
    }
}
