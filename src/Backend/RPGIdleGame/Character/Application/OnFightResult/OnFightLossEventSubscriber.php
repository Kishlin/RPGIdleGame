<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\OnFightResult;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadALossDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;

final class OnFightLossEventSubscriber implements DomainEventSubscriber
{
    public function __construct(
        private CharacterGateway $gateway,
    ) {
    }

    public function __invoke(FightParticipantHadALossDomainEvent $event): void
    {
        $character = $this->gateway->findOneById(
            CharacterId::fromOther($event->characterId()),
        );

        if (null === $character) {
            throw new CharacterNotFoundException();
        }

        $character->hadAFightLoss($event->fightDate());

        $this->gateway->save($character);
    }

    public static function subscribedTo(): array
    {
        return [
            FightParticipantHadALossDomainEvent::class,
        ];
    }
}
