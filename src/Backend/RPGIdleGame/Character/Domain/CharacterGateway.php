<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;

interface CharacterGateway
{
    public function save(Character $character): void;

    public function delete(CharacterId $characterId): void;

    public function findOneById(CharacterId $characterId): ?Character;
}
