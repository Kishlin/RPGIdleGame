<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Dice;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponentGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class InitiateAFightCommandHandler implements CommandHandler
{
    public function __construct(
        private FightInitiationAllowanceGateway $allowanceGateway,
        private FightInitiatorGateway $fightInitiatorGateway,
        private FightOpponentGateway $fightOpponentGateway,
        private FightGateway $fightGateway,
        private UuidGenerator $uuidGenerator,
        private Dice $dice,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws NoOpponentAvailableException|RequesterIsNotAllowedToInitiateFight
     */
    public function __invoke(InitiateAFightCommand $command): FightId
    {
        if ($this->requesterIsNotAllowedToFightWithFighter($command->requesterId(), $command->fighterId())) {
            $this->throwAnExceptionToDenyTheFight();
        }

        $fight = $this->initiateFight($command->fighterId());

        $fight->unfold($this->dice, $this->uuidGenerator);

        $this->fightGateway->save($fight);

        $this->eventDispatcher->dispatch(...$fight->pullDomainEvents());

        return $fight->id();
    }

    private function requesterIsNotAllowedToFightWithFighter(FightRequesterId $requesterId, FighterId $fighterId): bool
    {
        return false === $this->allowanceGateway->requesterIsAllowedToFightWithFighter($requesterId, $fighterId);
    }

    private function throwAnExceptionToDenyTheFight(): void
    {
        throw new RequesterIsNotAllowedToInitiateFight();
    }

    /**
     * @throws NoOpponentAvailableException
     */
    private function initiateFight(FighterId $fighterId): Fight
    {
        $initiator = $this->fightInitiatorGateway->createFromExternalDetailsOfInitiator($fighterId);
        $opponent  = $this->fightOpponentGateway->createFromExternalDetailsOfAnAvailableOpponent($fighterId);

        return Fight::initiate(new FightId($this->uuidGenerator->uuid4()), $initiator, $opponent);
    }
}
