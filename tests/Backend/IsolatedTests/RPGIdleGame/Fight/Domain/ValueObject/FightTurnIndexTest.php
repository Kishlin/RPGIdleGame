<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex
 */
final class FightTurnIndexTest extends TestCase
{
    public function testItCanGoToNextTurn(): void
    {
        self::assertSame(5, (new FightTurnIndex(4))->nextTurn()->value());
    }
}
