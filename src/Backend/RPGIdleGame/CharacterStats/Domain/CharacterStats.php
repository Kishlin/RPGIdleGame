<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId as CharacterId;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsDrawsCount as DrawsCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsFightsCount as FightsCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsLossesCount as LossesCount;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsWinsCount as WinsCount;

final class CharacterStats
{
    public function __construct(
        private CharacterId $characterId,
        private FightsCount $fightsCount,
        private WinsCount $winsCount,
        private DrawsCount $drawsCount,
        private LossesCount $lossesCount,
    ) {
    }

    public static function initiate(CharacterId $characterId): self
    {
        return new self($characterId, new FightsCount(0), new WinsCount(0), new DrawsCount(0), new LossesCount(0));
    }

    public static function fromScalars(CharacterId $characterId, int $wins = 0, int $draws = 0, int $losses = 0): self
    {
        return new self(
            $characterId,
            new FightsCount($wins + $draws + $losses),
            new WinsCount($wins),
            new DrawsCount($draws),
            new LossesCount($losses),
        );
    }

    public function incrementCountsOnFightWin(): void
    {
        $this->fightsCount = $this->fightsCount->increment();
        $this->winsCount   = $this->winsCount->increment();
    }

    public function incrementCountsOnFightDraw(): void
    {
        $this->fightsCount = $this->fightsCount->increment();
        $this->drawsCount  = $this->drawsCount->increment();
    }

    public function incrementCountsOnFightLoss(): void
    {
        $this->fightsCount = $this->fightsCount->increment();
        $this->lossesCount = $this->lossesCount->increment();
    }

    public function characterId(): CharacterId
    {
        return $this->characterId;
    }

    public function fightsCount(): FightsCount
    {
        return $this->fightsCount;
    }

    public function winsCount(): WinsCount
    {
        return $this->winsCount;
    }

    public function drawsCount(): DrawsCount
    {
        return $this->drawsCount;
    }

    public function lossesCount(): LossesCount
    {
        return $this->lossesCount;
    }
}
