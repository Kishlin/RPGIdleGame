<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue
 */
final class CharacterCountValueTest extends TestCase
{
    public function testItCanIncreasedAndDecreased(): void
    {
        $characterCountValue = new CharacterCountValue(5);

        self::assertSame(6, $characterCountValue->increment()->value());

        self::assertSame(4, $characterCountValue->decrement()->value());
    }

    public function testItCanTellWhenLimitIsReached(): void
    {
        $objectValue = 5;

        $aLimitBelowTheValue = 4;
        $aLimitAboveTheValue = 6;

        $characterCountValue = new CharacterCountValue($objectValue);

        self::assertTrue($characterCountValue->hasReachedLimit($objectValue));
        self::assertTrue($characterCountValue->hasReachedLimit($aLimitBelowTheValue));
        self::assertFalse($characterCountValue->hasReachedLimit($aLimitAboveTheValue));
    }
}
