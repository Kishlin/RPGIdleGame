<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommandHandler;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character\CharacterGatewaySpy;

trait FightServicesTrait
{
    private ?FightGatewaySpy $fightGatewaySpy = null;

    private ?FightParticipantGatewaySpy $fightParticipantGatewaySpy = null;

    private ?InitiateAFightCommandHandler $initiateAFightCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function characterGatewaySpy(): CharacterGatewaySpy;

    public function fightGatewaySpy(): FightGatewaySpy
    {
        if (null === $this->fightGatewaySpy) {
            $this->fightGatewaySpy = new FightGatewaySpy();
        }

        return $this->fightGatewaySpy;
    }

    public function fightParticipantGatewaySpy(): FightParticipantGatewaySpy
    {
        if (null === $this->fightParticipantGatewaySpy) {
            $this->fightParticipantGatewaySpy = new FightParticipantGatewaySpy($this->characterGatewaySpy());
        }

        return $this->fightParticipantGatewaySpy;
    }

    public function initiateAFightCommandHandler(): InitiateAFightCommandHandler
    {
        if (null === $this->initiateAFightCommandHandler) {
            $this->initiateAFightCommandHandler = new InitiateAFightCommandHandler(
                allowanceGateway: $this->fightParticipantGatewaySpy(),
                fightInitiatorGateway: $this->fightParticipantGatewaySpy(),
                fightOpponentGateway: $this->fightParticipantGatewaySpy(),
                fightGateway: $this->fightGatewaySpy(),
                uuidGenerator: new UuidGeneratorUsingRamsey(),
                dice: new RandomDice(),
                eventDispatcher: $this->eventDispatcher(),
            );
        }

        return $this->initiateAFightCommandHandler;
    }
}
