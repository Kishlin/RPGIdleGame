<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;

interface CharacterStatsGateway
{
    public function save(CharacterStats $characterStats): void;

    public function findOneById(CharacterStatsCharacterId $characterId): ?CharacterStats;
}
