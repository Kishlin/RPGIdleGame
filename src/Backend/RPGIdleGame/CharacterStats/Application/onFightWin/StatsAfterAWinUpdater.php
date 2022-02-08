<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Application\onFightWin;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Application\AbstractStatsUpdater;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadAWinDomainEvent;

final class StatsAfterAWinUpdater extends AbstractStatsUpdater
{
    public function __invoke(FightParticipantHadAWinDomainEvent $event): void
    {
        $stats = $this->findStatsForCharacter($event->characterId());

        $stats->incrementCountsOnFightWin();

        $this->saveStats($stats);
    }

    public static function subscribedTo(): array
    {
        return [
            FightParticipantHadAWinDomainEvent::class,
        ];
    }
}
