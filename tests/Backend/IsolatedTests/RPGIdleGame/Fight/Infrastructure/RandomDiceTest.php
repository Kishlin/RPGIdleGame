<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Infrastructure;

use Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice
 */
final class RandomDiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItIsZeroWhenMaxIsNotStrictlyPositive(): void
    {
        $dice = new RandomDice();

        self::assertSame(0, $dice->roll(0));
        self::assertSame(0, $dice->roll(-1));
    }

    /**
     * @throws Exception
     */
    public function testItDoesNotRollOverTheMaxLimit(): void
    {
        $dice = new RandomDice();

        self::assertSame(1, $dice->roll(1));
        self::assertLessThanOrEqual(5, $dice->roll(5));
    }
}
