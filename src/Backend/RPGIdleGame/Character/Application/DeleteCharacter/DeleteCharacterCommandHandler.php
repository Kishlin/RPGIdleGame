<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterDeletedDomainEvent;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class DeleteCharacterCommandHandler
{
    public function __construct(
        private DeletionAllowanceGateway $canDeleteGateway,
        private CharacterGateway $characterGateway,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws DeletionIsNotAllowedException
     */
    public function __invoke(DeleteCharacterCommand $command): void
    {
        $characterToDeleteId     = $command->characterId();
        $ownerRequestingDeletion = $command->accountRequestingDeletion();

        if (false === $this->canDeleteGateway->requesterIsTheRightfulOwner($ownerRequestingDeletion, $characterToDeleteId)) {
            throw new DeletionIsNotAllowedException();
        }

        $domainEvent = new CharacterDeletedDomainEvent($characterToDeleteId, $ownerRequestingDeletion);

        $this->characterGateway->delete($command->characterId());
        $this->eventDispatcher->dispatch($domainEvent);
    }
}
