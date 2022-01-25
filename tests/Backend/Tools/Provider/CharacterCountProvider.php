<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;

final class CharacterCountProvider
{
    public static function freshCount(): CharacterCount
    {
        return CharacterCount::createForOwner(new CharacterCountOwner('758e029d-e8d3-4593-993f-922177094404'));
    }

    public static function countWithAFewCharacters(): CharacterCount
    {
        $characterCount = CharacterCount::createForOwner(
            new CharacterCountOwner('c4519ca2-45ed-49a4-af50-84d49a1fe6e4'),
        );

        foreach (range(1, 5) as $i) {
            $characterCount->onCreatedACharacter();
        }

        return $characterCount;
    }

    public static function countAtTheLimitOfCharacters(): CharacterCount
    {
        $characterCount = CharacterCount::createForOwner(
            new CharacterCountOwner('fa4f069e-432d-498e-9252-71977853603f'),
        );

        foreach (range(1, CharacterCount::CHARACTER_LIMIT) as $i) {
            $characterCount->onCreatedACharacter();
        }

        return $characterCount;
    }
}
