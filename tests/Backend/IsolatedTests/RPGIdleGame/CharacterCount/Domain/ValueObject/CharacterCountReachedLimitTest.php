<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountReachedLimit;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountReachedLimit
 */
final class CharacterCountReachedLimitTest extends TestCase
{
    public function testItCanBeFlaggedAsReachedOrUnreached(): void
    {
        $characterCountReachedLimit = new CharacterCountReachedLimit(false);
        self::assertLimitIsNotReached($characterCountReachedLimit);

        $limitReached = $characterCountReachedLimit->flagLimitAsReached();
        self::assertLimitIsReached($limitReached);

        $limitNotReached = $characterCountReachedLimit->limitIsNotReachedAnymore();
        self::assertLimitIsNotReached($limitNotReached);
    }

    private static function assertLimitIsReached(CharacterCountReachedLimit $characterCountReachedLimit): void
    {
        self::assertTrue($characterCountReachedLimit->value());
    }

    private static function assertLimitIsNotReached(CharacterCountReachedLimit $characterCountReachedLimit): void
    {
        self::assertFalse($characterCountReachedLimit->value());
    }
}
