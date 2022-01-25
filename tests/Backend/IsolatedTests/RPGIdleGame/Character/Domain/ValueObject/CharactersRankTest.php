<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank
 */
final class CharactersRankTest extends TestCase
{
    public function testItCanRankUp(): void
    {
        $rank = new CharacterRank(10);

        self::assertSame(11, $rank->rankUp()->value());
    }

    public function testItWillNotRankDownPastRankOne(): void
    {
        $rank = new CharacterRank(2);

        $unrankedOnce = $rank->rankDownIfItCan();

        self::assertSame(1, $unrankedOnce->value());

        $unrankedTwice = $unrankedOnce->rankDownIfItCan();

        self::assertSame(1, $unrankedTwice->rankDownIfItCan()->value());
    }
}
