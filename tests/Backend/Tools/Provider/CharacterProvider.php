<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use DateTimeImmutable;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

final class CharacterProvider
{
    public static function freshCharacter(): Character
    {
        return Character::createFresh(
            new CharacterId('9f7c029c-bd83-4e7f-bf37-c973671bc8b7'),
            new CharacterName('Kishlin'),
            new CharacterOwner('e880eafd-b195-4f18-b140-fd196aaac21a'),
            new DateTimeImmutable(),
        );
    }

    /**
     * @throws ReflectionException
     */
    public static function tweakedCharacter(
        int $skillPoints = 12,
        int $healthPoints = 10,
        int $attackPoints = 0,
        int $defensePoints = 0,
        int $magikPoints = 0,
    ): Character {
        $character = self::freshCharacter();

        ReflectionHelper::writePropertyValue($character, 'skillPoint', new CharacterSkillPoint($skillPoints));
        ReflectionHelper::writePropertyValue($character, 'health', new CharacterHealth($healthPoints));
        ReflectionHelper::writePropertyValue($character, 'attack', new CharacterAttack($attackPoints));
        ReflectionHelper::writePropertyValue($character, 'defense', new CharacterDefense($defensePoints));
        ReflectionHelper::writePropertyValue($character, 'magik', new CharacterMagik($magikPoints));

        return $character;
    }

    /**
     * @throws ReflectionException
     */
    public static function completeCharacter(): Character
    {
        $character = self::tweakedCharacter(50, 85, 36, 28, 30);

        ReflectionHelper::writePropertyValue($character, 'rank', new CharacterRank(15));

        return $character;
    }

    /**
     * @param array<string, object> $overrides List of fields to override, array keys are the target property names
     *
     * @throws ReflectionException
     */
    public static function customCharacter(array $overrides): Character
    {
        $character = self::freshCharacter();

        foreach ($overrides as $property => $value) {
            ReflectionHelper::writePropertyValue($character, $property, $value);
        }

        return $character;
    }
}
