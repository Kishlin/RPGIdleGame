<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class DeleteCharacterCommandHandler
{
    public function __construct(
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

        $character = $this->characterGateway->findOneByIdAndOwner($characterToDeleteId, $ownerRequestingDeletion);

        if (null === $character) {
            throw new DeletionIsNotAllowedException();
        }

        $character->softDelete();

        $this->characterGateway->save($character);

        $this->eventDispatcher->dispatch(...$character->pullDomainEvents());
    }
}
