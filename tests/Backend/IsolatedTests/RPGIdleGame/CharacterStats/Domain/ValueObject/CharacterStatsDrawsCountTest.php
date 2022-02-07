<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterStats\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsDrawsCount;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsDrawsCount
 */
final class CharacterStatsDrawsCountTest extends TestCase
{
    public function testItCanIncrementItself(): void
    {
        $fightsCount = new CharacterStatsDrawsCount(10);

        self::assertSame(11, $fightsCount->increment()->value());
    }
}
