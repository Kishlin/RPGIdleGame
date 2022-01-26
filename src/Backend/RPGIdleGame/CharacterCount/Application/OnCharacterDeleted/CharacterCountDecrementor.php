<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterDeleted;

use Doctrine\Common\EventSubscriber;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterDeletedDomainEvent;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Application\CharacterUpdateEventListenerTrait;

final class CharacterCountDecrementor implements EventSubscriber
{
    use CharacterUpdateEventListenerTrait;

    public function __invoke(CharacterDeletedDomainEvent $event): void
    {
        $characterCount = $this->findCharacterCount($event->characterOwner());

        $characterCount->decrementOnCharacterDeletion();

        $this->characterCountGateway->save($characterCount);
    }

    public function getSubscribedEvents(): array
    {
        return [
            CharacterDeletedDomainEvent::eventName(),
        ];
    }
}
