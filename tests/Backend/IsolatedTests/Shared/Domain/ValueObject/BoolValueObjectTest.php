<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject
 */
final class BoolValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToBoolean(): void
    {
        self::assertFalse((new class(false) extends BoolValueObject {})->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class(true) extends BoolValueObject {};

        $shouldBeEqual = new class(true) extends BoolValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class(false) extends BoolValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
