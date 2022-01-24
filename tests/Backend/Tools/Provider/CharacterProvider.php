<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

final class CharacterProvider
{
    public static function freshCharacter(): Character
    {
        return Character::createFresh(
            new CharacterId('9f7c029c-bd83-4e7f-bf37-c973671bc8b7'),
            new CharacterName('Kishlin'),
            new CharacterOwner('e880eafd-b195-4f18-b140-fd196aaac21a'),
        );
    }
}
