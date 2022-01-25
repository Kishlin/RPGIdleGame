<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Backend\Account\Application\Signup\AnAccountAlreadyUsesTheEmailException;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Account\Domain\AccountPassword;
use Kishlin\Backend\Account\Domain\AccountUsername;
use PHPUnit\Framework\Assert;
use ReflectionException;
use Throwable;

final class AccountContext extends RPGIdleGameContext implements Context
{
    private const EMAIL_TO_USE          = 'user@example.com';
    private const NEW_ACCOUNT_UUID      = '51cefa3e-c223-469e-a23c-61a32e4bf048';
    private const EXISTING_ACCOUNT_UUID = '255c03d2-4149-4fe2-b922-65ed3ce4be0e';

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
     *
     * @throws ReflectionException
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
}
