<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

interface CharacterGateway
{
    public function save(Character $character): void;

    public function delete(CharacterId $characterId): void;

    public function findOneById(CharacterId $characterId): ?Character;

    /**
     * @return Character[]
     */
    public function findAllForOwner(CharacterOwner $characterOwner): array;

    public function ownerAlreadyHasACharacterWithName(CharacterName $characterName, CharacterOwner $characterOwner): bool;
}
