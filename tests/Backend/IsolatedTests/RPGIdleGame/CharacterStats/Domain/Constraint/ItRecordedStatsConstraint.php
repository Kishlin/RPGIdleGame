<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterStats\Domain\Constraint;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsDrawsCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsFightsCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsLossesCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsWinsCount;
use PHPUnit\Framework\Constraint\Constraint;

final class ItRecordedStatsConstraint extends Constraint
{
    public function __construct(
        private int $wins,
        private int $draws,
        private int $losses,
    ) {
    }

    public function toString(): string
    {
        return "recorded the stats to be {$this->wins}:{$this->draws}:{$this->losses}";
    }

    /**
     * {@inheritDoc}
     *
     * @param CharacterStats $other
     */
    protected function matches($other): bool
    {
        return $other->winsCount()->equals(new CharacterStatsWinsCount($this->wins))
            && $other->drawsCount()->equals(new CharacterStatsDrawsCount($this->draws))
            && $other->lossesCount()->equals(new CharacterStatsLossesCount($this->losses))
            && $other->fightsCount()->equals(new CharacterStatsFightsCount($this->wins + $this->draws + $this->losses))
        ;
    }
}
