<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterCount\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount
 */
final class CharacterCountTest extends TestCase
{
    public function testItCanBeCreatedWithACountAtZero(): void
    {
        $ownerId = new CharacterCountOwner('758e029d-e8d3-4593-993f-922177094404');

        $characterCount = CharacterCount::createForOwner($ownerId);

        self::assertSame(0, $characterCount->count()->value());
    }
}
