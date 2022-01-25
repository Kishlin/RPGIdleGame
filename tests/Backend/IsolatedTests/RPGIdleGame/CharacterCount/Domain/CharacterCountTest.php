<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterCount\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount
 */
final class CharacterCountTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testItCanBeCreatedWithACountAtZero(): void
    {
        $ownerId = new CharacterCountOwner('758e029d-e8d3-4593-993f-922177094404');

        $characterCount = CharacterCount::createForOwner($ownerId);

        $characterCountValue = ReflectionHelper::propertyValue($characterCount, 'characterCountValue');

        self::assertInstanceOf(CharacterCountValue::class, $characterCountValue);
        self::assertSame(0, $characterCountValue->value());
    }
}
