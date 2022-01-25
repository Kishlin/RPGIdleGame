<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\RepositorySpy;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;

class CharacterGatewaySpy implements CharacterGateway
{
    /** @var array<string, Character> */
    private array $characters = [];

    public function save(Character $character): void
    {
        $this->characters[$character->characterId()->value()] = $character;
    }

    public function delete(CharacterId $characterId): void
    {
        unset($this->characters[$characterId->value()]);
    }

    public function findOneById(CharacterId $characterId): ?Character
    {
        return $this->characters[$characterId->value()] ?? null;
    }

    public function findAllForOwner(CharacterOwner $characterOwner): array
    {
        return array_filter($this->characters, static function (Character $character) use ($characterOwner) {
            return $characterOwner->equals(self::characterOwner($character));
        });
    }

    public function ownerAlreadyHasACharacterWithName(CharacterName $characterName, CharacterOwner $characterOwner): bool
    {
        foreach ($this->characters as $character) {
            if (
                $characterName->equals(self::characterName($character))
                && $characterOwner->equals(self::characterOwner($character))
            ) {
                return true;
            }
        }

        return false;
    }

    public function has(CharacterId $characterId): bool
    {
        return array_key_exists($characterId->value(), $this->characters);
    }

    public static function characterOwner(Character $character): CharacterOwner
    {
        $characterOwner = ReflectionHelper::propertyValue($character, 'characterOwner');
        assert($characterOwner instanceof CharacterOwner);

        return $characterOwner;
    }

    public static function characterName(Character $character): CharacterName
    {
        $characterName = ReflectionHelper::propertyValue($character, 'characterName');
        assert($characterName instanceof CharacterName);

        return $characterName;
    }
}
