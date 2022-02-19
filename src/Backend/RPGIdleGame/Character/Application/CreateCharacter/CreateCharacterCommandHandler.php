<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class CreateCharacterCommandHandler implements CommandHandler
{
    public function __construct(
        private CreationAllowanceGateway $canCreateCharacterGateway,
        private CharacterGateway $characterGateway,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws CreationLimitCheckerDoesNotExistException|HasReachedCharacterLimitException
     */
    public function __invoke(CreateCharacterCommand $command): CharacterId
    {
        if (true === $this->canCreateCharacterGateway->ownerHasReachedCharacterLimit($command->characterOwner())) {
            throw new HasReachedCharacterLimitException();
        }

        $character = Character::createFresh(
            $command->characterId(),
            $command->characterName(),
            $command->characterOwner(),
        );

        $this->characterGateway->save($character);

        $this->eventDispatcher->dispatch(...$character->pullDomainEvents());

        return $character->id();
    }
}
