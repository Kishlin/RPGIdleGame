<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\RepositorySpy;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

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
        $filterForOwner = static function (Character $character) use ($characterOwner) {
            return $characterOwner->equals($character->characterOwner());
        };

        return array_filter($this->characters, $filterForOwner);
    }

    public function ownerAlreadyHasACharacterWithName(CharacterName $characterName, CharacterOwner $characterOwner): bool
    {
        foreach ($this->characters as $character) {
            if (
                $characterName->equals($character->characterName())
                && $characterOwner->equals($character->characterOwner())
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
}
