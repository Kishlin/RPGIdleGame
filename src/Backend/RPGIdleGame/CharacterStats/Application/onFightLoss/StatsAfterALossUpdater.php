<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Application\onFightLoss;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Application\AbstractStatsUpdater;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadALossDomainEvent;

final class StatsAfterALossUpdater extends AbstractStatsUpdater
{
    public function __invoke(FightParticipantHadALossDomainEvent $event): void
    {
        $stats = $this->findStatsForCharacter($event->characterId());

        $stats->incrementCountsOnFightLoss();

        $this->saveStats($stats);
    }

    public static function subscribedTo(): array
    {
        return [
            FightParticipantHadALossDomainEvent::class,
        ];
    }
}
