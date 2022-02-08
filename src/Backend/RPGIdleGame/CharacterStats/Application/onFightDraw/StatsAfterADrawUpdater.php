<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Application\onFightDraw;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Application\AbstractStatsUpdater;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadADrawDomainEvent;

final class StatsAfterADrawUpdater extends AbstractStatsUpdater
{
    public function __invoke(FightParticipantHadADrawDomainEvent $event): void
    {
        $stats = $this->findStatsForCharacter($event->characterId());

        $stats->incrementCountsOnFightDraw();

        $this->saveStats($stats);
    }

    public static function subscribedTo(): array
    {
        return [
            FightParticipantHadADrawDomainEvent::class,
        ];
    }
}
