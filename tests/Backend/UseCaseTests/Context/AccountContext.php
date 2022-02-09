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
use Kishlin\Backend\Account\Domain\View\JsonableAuthentication;
use Kishlin\Backend\Account\Domain\View\JsonableSimpleAuthentication;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use PHPUnit\Framework\Assert;
use Throwable;

final class AccountContext extends RPGIdleGameContext
{
    private JsonableAuthentication|JsonableSimpleAuthentication|null $authentication = null;

    private ?AccountId $accountId       = null;
    private ?Throwable $exceptionThrown = null;

    /**
     * @Given /^a client has an account$/
     */
    public function aClientHasAnAccount(): void
    {
        self::container()->accountGatewaySpy()->save(Account::createActiveAccount(
            new AccountId(self::CLIENT_UUID),
            new AccountUsername('User'),
            new AccountPassword('password'),
            new AccountEmail('email@example.com'),
            new AccountSalt('salt'),
        ));

        self::container()->characterCountGatewaySpy()->save(CharacterCount::createForOwner(
            new CharacterCountOwner(self::CLIENT_UUID),
        ));
    }

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

            assert($response instanceof JsonableAuthentication);

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

            assert($response instanceof JsonableAuthentication);

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

            assert($response instanceof JsonableSimpleAuthentication);

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

            assert($response instanceof JsonableSimpleAuthentication);

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
        Assert::assertInstanceOf(JsonableSimpleAuthentication::class, $this->authentication);
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
