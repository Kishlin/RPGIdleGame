<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommandHandler;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommandHandler;
use Kishlin\Backend\Account\Application\Signup\SignupCommandHandler;
use Kishlin\Backend\Account\Infrastructure\AuthenticationGeneratorUsingFirebase;
use Kishlin\Backend\Account\Infrastructure\SaltGeneratorUsingRandomBytes;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;
use Kishlin\Backend\Shared\Infrastructure\Security\RefreshTokenParserUsingFirebase;

trait AccountServicesTrait
{
    private ?RefreshTokenParserUsingFirebase $refreshTokenParser = null;

    private ?AuthenticationGeneratorUsingFirebase $authenticationGenerator = null;

    private ?SaltGeneratorUsingRandomBytes $saltGenerator = null;

    private ?AccountGatewaySpy $accountGatewaySpy = null;

    private ?AuthenticateCommandHandler $authenticateCommandHandler = null;

    private ?RefreshAuthenticationCommandHandler $refreshAuthenticationCommandHandler = null;

    private ?SignupCommandHandler $signupCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    public function refreshTokenParser(): RefreshTokenParserUsingFirebase
    {
        if (null === $this->refreshTokenParser) {
            $this->refreshTokenParser = new RefreshTokenParserUsingFirebase(
                'ThisKeyIsNotSoSecretButItIsTests',
                'HS256',
                false,
            );
        }

        return $this->refreshTokenParser;
    }

    public function authenticationGenerator(): AuthenticationGeneratorUsingFirebase
    {
        if (null === $this->authenticationGenerator) {
            $this->authenticationGenerator = new AuthenticationGeneratorUsingFirebase(
                new JWTGeneratorUsingFirebase(
                    'ThisKeyIsNotSoSecretButItIsTests',
                    'test.rpgidlegame.com',
                    'HS256',
                ),
                false,
            );
        }

        return $this->authenticationGenerator;
    }

    public function saltGenerator(): SaltGeneratorUsingRandomBytes
    {
        if (null === $this->saltGenerator) {
            $this->saltGenerator = new SaltGeneratorUsingRandomBytes();
        }

        return $this->saltGenerator;
    }

    public function accountGatewaySpy(): AccountGatewaySpy
    {
        if (null === $this->accountGatewaySpy) {
            $this->accountGatewaySpy = new AccountGatewaySpy();
        }

        return $this->accountGatewaySpy;
    }

    public function authenticateCommandHandler(): AuthenticateCommandHandler
    {
        if (null === $this->authenticateCommandHandler) {
            $this->authenticateCommandHandler = new AuthenticateCommandHandler(
                $this->authenticationGenerator(),
                $this->accountGatewaySpy(),
            );
        }

        return $this->authenticateCommandHandler;
    }

    public function refreshAuthenticationCommandHandler(): RefreshAuthenticationCommandHandler
    {
        if (null === $this->refreshAuthenticationCommandHandler) {
            $this->refreshAuthenticationCommandHandler = new RefreshAuthenticationCommandHandler(
                $this->refreshTokenParser(),
                $this->accountGatewaySpy(),
                $this->authenticationGenerator(),
            );
        }

        return $this->refreshAuthenticationCommandHandler;
    }

    public function signupCommandHandler(): SignupCommandHandler
    {
        if (null === $this->signupCommandHandler) {
            $this->signupCommandHandler = new SignupCommandHandler(
                $this->accountGatewaySpy(),
                $this->accountGatewaySpy(),
                $this->accountGatewaySpy(),
                $this->saltGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->signupCommandHandler;
    }
}
