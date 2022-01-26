<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject
 */
final class PositiveIntValueObjectTest extends TestCase
{
    public function testItAcceptsToBePositive(): void
    {
        self::assertSame(
            1,
            (new class(1) extends PositiveIntValueObject {})->value(),
        );
    }

    public function testItAcceptsToBeZero(): void
    {
        self::assertSame(
            0,
            (new class(0) extends PositiveIntValueObject {})->value(),
        );
    }

    public function testItRefusesToBeNegative(): void
    {
        $negativeInt = -1;

        self::expectException(InvalidValueException::class);
        new class($negativeInt) extends PositiveIntValueObject {};
    }
}
