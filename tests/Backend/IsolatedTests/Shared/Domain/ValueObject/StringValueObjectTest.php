<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject
 */
final class StringValueObjectTest extends TestCase
{
    public function testItCanBeCreatedAndConvertedBackToString(): void
    {
        self::assertSame(
            'rpgidlegame',
            (new class('rpgidlegame') extends StringValueObject {})->value(),
        );
    }

    public function testItCanCompareItselfToAnotherInstance(): void
    {
        $reference = new class('rpgidlegame') extends StringValueObject {};

        $shouldBeEqual = new class('rpgidlegame') extends StringValueObject {};
        self::assertTrue($reference->equals($shouldBeEqual));

        $shouldNotBeEqual = new class('app') extends StringValueObject {};
        self::assertFalse($reference->equals($shouldNotBeEqual));
    }
}
