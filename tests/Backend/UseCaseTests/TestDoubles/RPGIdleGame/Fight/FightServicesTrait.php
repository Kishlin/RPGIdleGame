<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommandHandler;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQueryHandler;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterQueryHandler;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Backend\Shared\Infrastructure\Time\SystemClock;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character\CharacterGatewaySpy;

trait FightServicesTrait
{
    private ?FightGatewaySpy $fightGatewaySpy = null;

    private ?FightParticipantGatewaySpy $fightParticipantGatewaySpy = null;

    private ?InitiateAFightCommandHandler $initiateAFightCommandHandler = null;

    private ?ViewFightQueryHandler $viewFightQueryHandler = null;

    private ?ViewFightsForFighterQueryHandler $fightsForFighterQueryHandler = null;

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
                clock: new SystemClock(),
                dice: new RandomDice(),
                eventDispatcher: $this->eventDispatcher(),
            );
        }

        return $this->initiateAFightCommandHandler;
    }

    public function viewFightQueryHandler(): ViewFightQueryHandler
    {
        if (null === $this->viewFightQueryHandler) {
            $this->viewFightQueryHandler = new ViewFightQueryHandler($this->fightGatewaySpy());
        }

        return $this->viewFightQueryHandler;
    }

    public function fightsForFighterQueryHandler(): ViewFightsForFighterQueryHandler
    {
        if (null === $this->fightsForFighterQueryHandler) {
            $this->fightsForFighterQueryHandler = new ViewFightsForFighterQueryHandler($this->fightGatewaySpy());
        }

        return $this->fightsForFighterQueryHandler;
    }
}
