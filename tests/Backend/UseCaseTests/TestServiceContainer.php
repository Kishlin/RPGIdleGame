<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Backend\Account\Application\Signup\SignupCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\RepositorySpy\AccountGatewaySpy;

final class TestServiceContainer
{
    private ?TestCommandBus $testCommandBus = null;

    private ?TestEventDispatcher $testEventDispatcher = null;

    private ?TestQueryBus $testQueryBus = null;

    private ?AccountGatewaySpy $accountGatewaySpy = null;

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

    public function signupCommandHandler(): SignupCommandHandler
    {
        return new SignupCommandHandler($this->accountGatewaySpy(), $this->eventDispatcher());
    }

    public function accountGatewaySpy(): AccountGatewaySpy
    {
        if (null === $this->accountGatewaySpy) {
            $this->accountGatewaySpy = new AccountGatewaySpy();
        }

        return $this->accountGatewaySpy;
    }
}
