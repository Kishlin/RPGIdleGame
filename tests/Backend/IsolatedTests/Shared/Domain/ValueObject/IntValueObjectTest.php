<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject
 */
final class IntValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToInt(): void
    {
        self::assertSame(
            42,
            (new class(42) extends IntValueObject {})->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class(42) extends IntValueObject {};

        $shouldBeEqual = new class(42) extends IntValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class(50) extends IntValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }

    public function testItCanBeCreatedFromOtherInt(): void
    {
        $other = new class(42) extends IntValueObject {};

        self::assertTrue($other::fromOther($other)->equals($other));
    }
}
