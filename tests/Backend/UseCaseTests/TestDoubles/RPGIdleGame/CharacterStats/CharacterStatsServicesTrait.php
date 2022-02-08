<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterStats;

trait CharacterStatsServicesTrait
{
    private ?CharacterStatsGatewaySpy $characterStatsGatewaySpy = null;

    public function characterStatsGatewaySpy(): CharacterStatsGatewaySpy
    {
        if (null === $this->characterStatsGatewaySpy) {
            $this->characterStatsGatewaySpy = new CharacterStatsGatewaySpy();
        }

        return $this->characterStatsGatewaySpy;
    }
}
