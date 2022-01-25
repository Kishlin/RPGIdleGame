<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterFightsCount;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterFightsCount
 */
final class CharacterFightsCountTest extends TestCase
{
    public function testItIncrementsWhenItTakesPartInAFight(): void
    {
        $fightsCount = new CharacterFightsCount(10);

        self::assertSame(11, $fightsCount->tookPartInAFight()->value());
    }
}
