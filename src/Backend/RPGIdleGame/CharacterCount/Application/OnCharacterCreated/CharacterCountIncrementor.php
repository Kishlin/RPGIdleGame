<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterCreated;

use Doctrine\Common\EventSubscriber;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;

final class CharacterCountIncrementor implements EventSubscriber
{
    public function __construct(
        private CharacterCountGateway $characterCountGateway,
    ) {
    }

    /**
     * @throws CharacterCountNotFoundException
     */
    public function __invoke(CharacterCreatedDomainEvent $event): void
    {
        $characterCount = $this->findCharacterCount($event);

        $characterCount->incrementOnCharacterCreation();

        $this->characterCountGateway->save($characterCount);
    }

    public function getSubscribedEvents(): array
    {
        return [
            CharacterCreatedDomainEvent::eventName(),
        ];
    }

    /**
     * @throws CharacterCountNotFoundException
     */
    private function findCharacterCount(CharacterCreatedDomainEvent $event): CharacterCount
    {
        $characterCountOwner = CharacterCountOwner::fromOther($event->characterOwner());
        $characterCount      = $this->characterCountGateway->findForOwner($characterCountOwner);

        if (null === $characterCount) {
            throw new CharacterCountNotFoundException();
        }

        return $characterCount;
    }
}
