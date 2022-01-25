<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

final class CharacterCountProvider
{
    public static function freshCount(): CharacterCount
    {
        return CharacterCount::createForOwner(new CharacterCountOwner('758e029d-e8d3-4593-993f-922177094404'));
    }

    /**
     * @throws ReflectionException
     */
    public static function countWithAFewCharacters(): CharacterCount
    {
        $characterCount = CharacterCount::createForOwner(
            new CharacterCountOwner('c4519ca2-45ed-49a4-af50-84d49a1fe6e4'),
        );

        ReflectionHelper::writePropertyValue($characterCount, 'characterCountValue', new CharacterCountValue(5));

        return $characterCount;
    }
}
