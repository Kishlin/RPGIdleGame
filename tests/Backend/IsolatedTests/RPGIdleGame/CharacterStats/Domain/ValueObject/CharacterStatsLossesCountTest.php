<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterStats\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsLossesCount;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsLossesCount
 */
final class CharacterStatsLossesCountTest extends TestCase
{
    public function testItCanIncrementItself(): void
    {
        $fightsCount = new CharacterStatsLossesCount(10);

        self::assertSame(11, $fightsCount->increment()->value());
    }
}
