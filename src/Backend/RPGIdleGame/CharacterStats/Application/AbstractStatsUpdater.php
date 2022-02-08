<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Application;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStatsGateway;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStatsNotFoundException;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

abstract class AbstractStatsUpdater implements DomainEventSubscriber
{
    public function __construct(
        private CharacterStatsGateway $gateway,
    ) {
    }

    protected function findStatsForCharacter(UuidValueObject $characterId): CharacterStats
    {
        $stats = $this->gateway->findForCharacter(
            CharacterStatsCharacterId::fromOther($characterId)
        );

        if (null === $stats) {
            throw new CharacterStatsNotFoundException();
        }

        return $stats;
    }

    protected function saveStats(CharacterStats $stats): void
    {
        $this->gateway->save($stats);
    }
}
