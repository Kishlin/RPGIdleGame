<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterCreated;

use Doctrine\Common\EventSubscriber;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Application\CharacterUpdateEventListenerTrait;

final class CharacterCountIncrementorTrait implements EventSubscriber
{
    use CharacterUpdateEventListenerTrait;

    /**
     * @throws CharacterCountNotFoundException
     */
    public function __invoke(CharacterCreatedDomainEvent $event): void
    {
        $characterCount = $this->findCharacterCount($event->characterOwner());

        $characterCount->incrementOnCharacterCreation();

        $this->characterCountGateway->save($characterCount);
    }

    public function getSubscribedEvents(): array
    {
        return [
            CharacterCreatedDomainEvent::eventName(),
        ];
    }
}
