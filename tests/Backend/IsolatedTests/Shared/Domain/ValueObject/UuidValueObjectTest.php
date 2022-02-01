<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject
 */
final class UuidValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToString(): void
    {
        self::assertSame(
            'ccd760d9-2210-48fb-bcea-57767e52474a',
            (new class('ccd760d9-2210-48fb-bcea-57767e52474a') extends UuidValueObject {})->value(),
        );
    }

    public function testItValidatesInput(): void
    {
        $uuid = 'invalid uuid';

        self::expectException(InvalidValueException::class);
        new class($uuid) extends UuidValueObject {};
    }

    public function testItCannotBeNull(): void
    {
        self::expectException(InvalidValueException::class);
        new class(null) extends UuidValueObject {};
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class('1ca6cc42-7873-4abc-a6f7-54778a5d1d88') extends UuidValueObject {};

        $shouldBeEqual = new class('1ca6cc42-7873-4abc-a6f7-54778a5d1d88') extends UuidValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class('a0550e82-671a-4b5f-bc39-5a33f32c3673') extends UuidValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }

    public function testItCanBeCreatedFromOtherUuid(): void
    {
        $other = new class('62b5e641-3bad-476f-acf3-6982af60d543') extends UuidValueObject {};

        self::assertTrue($other::fromOther($other)->equals($other));
    }

    public function testItCanBeCreatedFromANullableUuid(): void
    {
        $nullableOther = new class('62b5e641-3bad-476f-acf3-6982af60d543') extends NullableUuidValueObject {};
        $other         = new class('62b5e641-3bad-476f-acf3-6982af60d543') extends UuidValueObject {};

        self::assertTrue($other::fromOther($nullableOther)->equals($other));

        $nullOther = new class(null) extends NullableUuidValueObject {};
        self::expectException(InvalidValueException::class);
        $other::fromOther($nullOther);
    }
}
