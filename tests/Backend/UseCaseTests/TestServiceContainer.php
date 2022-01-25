<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Backend\Account\Application\Signup\SignupCommandHandler;
use Kishlin\Backend\Account\Domain\AccountCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnAccountCreated\CharacterCountForOwnerCreator;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterCreated\CharacterCountIncrementor;
use Kishlin\Tests\Backend\UseCaseTests\RepositorySpy\AccountGatewaySpy;
use Kishlin\Tests\Backend\UseCaseTests\RepositorySpy\CharacterCountGatewaySpy;
use Kishlin\Tests\Backend\UseCaseTests\RepositorySpy\CharacterGatewaySpy;

final class TestServiceContainer
{
    private ?TestCommandBus $testCommandBus = null;

    private ?TestEventDispatcher $testEventDispatcher = null;

    private ?TestQueryBus $testQueryBus = null;

    private ?AccountGatewaySpy $accountGatewaySpy = null;

    private ?CharacterCountGatewaySpy $characterCountGatewaySpy = null;

    private ?CharacterGatewaySpy $characterGatewaySpy = null;

    public function commandBus(): TestCommandBus
    {
        if (null === $this->testCommandBus) {
            $this->testCommandBus = new TestCommandBus($this);
        }

        return $this->testCommandBus;
    }

    public function eventDispatcher(): TestEventDispatcher
    {
        if (null === $this->testEventDispatcher) {
            $this->testEventDispatcher = new TestEventDispatcher();

            $this->testEventDispatcher->addSubscriber(
                AccountCreatedDomainEvent::class,
                new CharacterCountForOwnerCreator($this->characterCountGatewaySpy(), $this->testEventDispatcher),
            );

            $this->testEventDispatcher->addSubscriber(
                CharacterCreatedDomainEvent::class,
                new CharacterCountIncrementor($this->characterCountGatewaySpy()),
            );
        }

        return $this->testEventDispatcher;
    }

    public function queryBus(): TestQueryBus
    {
        if (null === $this->testQueryBus) {
            $this->testQueryBus = new TestQueryBus($this);
        }

        return $this->testQueryBus;
    }

    public function accountGatewaySpy(): AccountGatewaySpy
    {
        if (null === $this->accountGatewaySpy) {
            $this->accountGatewaySpy = new AccountGatewaySpy();
        }

        return $this->accountGatewaySpy;
    }

    public function characterCountGatewaySpy(): CharacterCountGatewaySpy
    {
        if (null === $this->characterCountGatewaySpy) {
            $this->characterCountGatewaySpy = new CharacterCountGatewaySpy();
        }

        return $this->characterCountGatewaySpy;
    }

    public function characterGatewaySpy(): CharacterGatewaySpy
    {
        if (null === $this->characterGatewaySpy) {
            $this->characterGatewaySpy = new CharacterGatewaySpy();
        }

        return $this->characterGatewaySpy;
    }

    public function signupCommandHandler(): SignupCommandHandler
    {
        return new SignupCommandHandler($this->accountGatewaySpy(), $this->eventDispatcher());
    }

    public function createCharacterHandler(): CreateCharacterCommandHandler
    {
        return new CreateCharacterCommandHandler(
            $this->characterCountGatewaySpy(),
            $this->characterGatewaySpy(),
            $this->eventDispatcher()
        );
    }
}
