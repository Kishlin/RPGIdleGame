<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner
 */
final class CharacterCountOwnerTest extends TestCase
{
    public function testItCanBeCreatedFromOtherUuid(): void
    {
        $other = new class('62b5e641-3bad-476f-acf3-6982af60d543') extends UuidValueObject {};

        self::assertSame(
            $other->value(),
            CharacterCountOwner::fromOther($other)->value(),
        );
    }
}
