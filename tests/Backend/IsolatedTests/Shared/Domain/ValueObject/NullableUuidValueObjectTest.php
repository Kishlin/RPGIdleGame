<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject
 */
final class NullableUuidValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToString(): void
    {
        self::assertNull((new class(null) extends NullableUuidValueObject {})->value());

        $valueObject = new class('8eb25a89-af0e-4194-85b5-4b9f6d9aec57') extends NullableUuidValueObject {};

        self::assertSame('8eb25a89-af0e-4194-85b5-4b9f6d9aec57', $valueObject->value());
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class('ee638842-a3ed-4bd9-80c9-2804a1e65e7b') extends NullableUuidValueObject {};

        self::assertNotTrue($reference->equals(new class(null) extends NullableUuidValueObject {}));

        $shouldBeEqual = new class('ee638842-a3ed-4bd9-80c9-2804a1e65e7b') extends NullableUuidValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class('f13338b1-6493-4795-a3c5-943a2f812f74') extends NullableUuidValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));

        $nullReference         = new class(null) extends NullableUuidValueObject {};
        $nullThatShouldBeEqual = new class(null) extends NullableUuidValueObject {};
        self::assertTrue($nullReference->equals($nullThatShouldBeEqual));
    }

    public function testItCanCompareSelfToAUuidValueObject(): void
    {
        $nullableReference = new class('25d2122e-847c-43d5-86a6-f145d396ec1a') extends NullableUuidValueObject {};

        $shouldBeEqual = new class('25d2122e-847c-43d5-86a6-f145d396ec1a') extends UuidValueObject {};
        self::assertTrue($nullableReference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class('6dcdc673-d1d3-47db-b2a9-c28faec4cd20') extends UuidValueObject {};
        self::assertFalse($nullableReference->equals($shouldNotBeEqual));
    }
}
