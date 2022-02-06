<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Firebase\JWT\JWT;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticationDeniedException;
use Kishlin\Backend\Account\Application\RefreshAuthentication\CannotRefreshAuthenticationException;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommand;
use Kishlin\Backend\Account\Application\Signup\AnAccountAlreadyUsesTheEmailException;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountSalt;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Account\Domain\View\SerializableAuthentication;
use Kishlin\Backend\Account\Domain\View\SerializableSimpleAuthentication;
use PHPUnit\Framework\Assert;
use Throwable;

final class AccountContext extends RPGIdleGameContext
{
    private const CLIENT_UUID = '97c116cc-21b0-4624-8e02-88b9b1a977a7';

    private const EMAIL_TO_USE          = 'user@example.com';
    private const NEW_ACCOUNT_UUID      = '51cefa3e-c223-469e-a23c-61a32e4bf048';
    private const EXISTING_ACCOUNT_UUID = '255c03d2-4149-4fe2-b922-65ed3ce4be0e';

    private const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    private const ALGORITHM  = 'HS256';

    private SerializableAuthentication|SerializableSimpleAuthentication|null $authentication = null;

    private ?AccountId $accountId       = null;
    private ?Throwable $exceptionThrown = null;

    /**
     * @Given /^an account already exists with the email$/
     */
    public function anAccountAlreadyExistsWithTheEmail(): void
    {
        self::container()->accountGatewaySpy()->save(Account::createActiveAccount(
            new AccountId(self::EXISTING_ACCOUNT_UUID),
            new AccountUsername('Existing'),
            new AccountPassword('password'),
            new AccountEmail(self::EMAIL_TO_USE),
            new AccountSalt('salt'),
        ));
    }

    /**
     * @When /^a client creates an account$/
     * @When /^a client creates an account with the same email$/
     */
    public function aClientCreatesAnAccount(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                SignupCommand::fromScalars(
                    id: self::NEW_ACCOUNT_UUID,
                    username: 'User',
                    password: 'password',
                    email: self::EMAIL_TO_USE,
                )
            );

            assert($response instanceof AccountId);

            $this->accountId = $response;
        } catch (AnAccountAlreadyUsesTheEmailException $e) {
            $this->exceptionThrown = $e;
            $this->accountId       = null;
        }
    }

    /**
     * @When /^a client authenticates with the correct credentials$/
     */
    public function aClientAuthenticatesWithTheCorrectCredentials(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                AuthenticateCommand::fromScalars('User', 'password'),
            );

            assert($response instanceof SerializableAuthentication);

            $this->authentication  = $response;
            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
            $this->authentication  = null;
        }
    }

    /**
     * @When /^a client tries to authenticate with wrong credentials$/
     */
    public function aClientTriesToAuthenticateWithWrongCredentials(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                AuthenticateCommand::fromScalars('User', 'wrong'),
            );

            assert($response instanceof SerializableAuthentication);

            $this->authentication  = $response;
            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
            $this->authentication  = null;
        }
    }

    /**
     * @When /^a client refreshes its authentication with a valid refresh token$/
     */
    public function aClientRefreshesItsAuthenticationWithAValidRefreshToken(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                RefreshAuthenticationCommand::fromScalars(
                    JWT::encode(
                        payload: [
                            'userId' => self::CLIENT_UUID,
                            'salt'   => 'salt',
                            'iat'    => strtotime('now'),
                            'exp'    => strtotime('+1 month'),
                        ],
                        key: self::SECRET_KEY,
                        alg: self::ALGORITHM
                    ),
                ),
            );

            assert($response instanceof SerializableSimpleAuthentication);

            $this->authentication  = $response;
            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
            $this->authentication  = null;
        }
    }

    /**
     * @When /^a client tries to refresh with an expired refresh token$/
     */
    public function aClientTriesToRefreshWithAnExpiredRefreshToken(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                RefreshAuthenticationCommand::fromScalars(
                    JWT::encode(
                        payload: [
                            'userId' => self::CLIENT_UUID,
                            'salt'   => 'salt',
                            'iat'    => strtotime('-2 month'),
                            'exp'    => strtotime('-1 month'),
                        ],
                        key: self::SECRET_KEY,
                        alg: self::ALGORITHM
                    ),
                ),
            );

            assert($response instanceof SerializableSimpleAuthentication);

            $this->authentication  = $response;
            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
            $this->authentication  = null;
        }
    }

    /**
     * @Then /^its credentials are registered$/
     */
    public function itsCredentialsAreRegistered(): void
    {
        Assert::assertInstanceOf(AccountId::class, $this->accountId);

        Assert::assertContainsEquals(
            $this->accountId->value(),
            self::container()->accountGatewaySpy()->savedAccounts(),
        );
    }

    /**
     * @Then /^a fresh character count is registered$/
     */
    public function aFreshCharacterCounterIsRegistered(): void
    {
        Assert::assertNotNull($this->accountId);
        Assert::assertTrue(self::container()->characterCountGatewaySpy()->countForOwnerEquals($this->accountId, 0));
    }

    /**
     * @Then /^it did not register the new account$/
     */
    public function itDidRegisterTheNewAccount(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(AnAccountAlreadyUsesTheEmailException::class, $this->exceptionThrown);

        Assert::assertNotContains(
            self::NEW_ACCOUNT_UUID,
            self::container()->accountGatewaySpy()->savedAccounts(),
        );

        Assert::assertContainsEquals(
            self::EXISTING_ACCOUNT_UUID,
            self::container()->accountGatewaySpy()->savedAccounts(),
        );
    }

    /**
     * @Then /^the authentication was authorized$/
     */
    public function theAuthenticationWasAuthorized(): void
    {
        Assert::assertNotNull($this->authentication);
        Assert::assertNull($this->exceptionThrown);
    }

    /**
     * @Then /^the authentication was refused$/
     */
    public function theAuthenticationWasRefused(): void
    {
        Assert::assertNull($this->authentication);
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(AuthenticationDeniedException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^the renewed authentication was returned$/
     */
    public function theNewAuthenticationWasReturned(): void
    {
        Assert::assertNull($this->exceptionThrown);
        Assert::assertInstanceOf(SerializableSimpleAuthentication::class, $this->authentication);
    }

    /**
     * @Then /^renewing the authentication was refused$/
     */
    public function renewingTheAuthenticationWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(CannotRefreshAuthenticationException::class, $this->exceptionThrown);
    }
}
