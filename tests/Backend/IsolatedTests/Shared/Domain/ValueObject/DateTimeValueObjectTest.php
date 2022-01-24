<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject
 */
final class DateTimeValueObjectTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanBeCreatedAndConvertedBackToDateTimeImmutable(): void
    {
        $dateTime = '22-11-1993 01:00';

        self::assertEqualsCanonicalizing(
            new DateTimeImmutable($dateTime),
            (new class(new DateTimeImmutable($dateTime)) extends DateTimeValueObject {})->value(),
        );
    }

    public function testItCanCompareItselfToAnotherDateTimeImmutable(): void
    {
        $reference = new class(new DateTimeImmutable('22-11-1993 01:00')) extends DateTimeValueObject {};

        $shouldBeEqual = new class(new DateTimeImmutable('22-11-1993 01:00')) extends DateTimeValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class(new DateTimeImmutable('12-05-1997 12:00')) extends DateTimeValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
