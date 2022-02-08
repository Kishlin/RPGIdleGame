<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterStats;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStatsGateway;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;

final class CharacterStatsGatewaySpy implements CharacterStatsGateway
{
    /** @var array<string, CharacterStats> */
    private array $characterStats = [];

    public function save(CharacterStats $characterStats): void
    {
        $this->characterStats[$characterStats->characterId()->value()] = $characterStats;
    }

    public function findForCharacter(CharacterStatsCharacterId $characterId): ?CharacterStats
    {
        return $this->characterStats[$characterId->value()] ?? null;
    }
}
