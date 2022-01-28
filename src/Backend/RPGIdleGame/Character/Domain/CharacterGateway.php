<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

/**
 * Saves, finds, and delete, Character entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface CharacterGateway
{
    public function save(Character $character): void;

    public function delete(CharacterId $characterId): void;

    public function findOneById(CharacterId $characterId): ?Character;

    public function findOneByIdAndOwner(CharacterId $characterId, CharacterOwner $requester): ?Character;
}
