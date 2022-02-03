<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommandHandler;
use Kishlin\Backend\Account\Application\Signup\SignupCommandHandler;
use Kishlin\Backend\Account\Infrastructure\SaltGeneratorUsingRandomBytes;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

trait AccountServicesTrait
{
    private ?AccountGatewaySpy $accountGatewaySpy = null;

    private ?AuthenticationGeneratorStub $authenticationGeneratorStub = null;

    private ?AuthenticateCommandHandler $authenticateCommandHandler = null;

    private ?SignupCommandHandler $signupCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    public function accountGatewaySpy(): AccountGatewaySpy
    {
        if (null === $this->accountGatewaySpy) {
            $this->accountGatewaySpy = new AccountGatewaySpy();
        }

        return $this->accountGatewaySpy;
    }

    public function authenticationGeneratorStub(): AuthenticationGeneratorStub
    {
        if (null === $this->authenticationGeneratorStub) {
            $this->authenticationGeneratorStub = new AuthenticationGeneratorStub();
        }

        return $this->authenticationGeneratorStub;
    }

    public function authenticateCommandHandler(): AuthenticateCommandHandler
    {
        if (null === $this->authenticateCommandHandler) {
            $this->authenticateCommandHandler = new AuthenticateCommandHandler(
                $this->authenticationGeneratorStub(),
                $this->accountGatewaySpy(),
            );
        }

        return $this->authenticateCommandHandler;
    }

    public function signupCommandHandler(): SignupCommandHandler
    {
        if (null === $this->signupCommandHandler) {
            $this->signupCommandHandler = new SignupCommandHandler(
                $this->accountGatewaySpy(),
                $this->accountGatewaySpy(),
                new SaltGeneratorUsingRandomBytes(),
                $this->eventDispatcher(),
            );
        }

        return $this->signupCommandHandler;
    }
}
