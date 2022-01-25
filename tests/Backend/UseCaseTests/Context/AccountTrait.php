<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\Account\Application\Signup\AnAccountAlreadyUsesTheEmailException;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Account\Domain\AccountPassword;
use Kishlin\Backend\Account\Domain\AccountUsername;
use PHPUnit\Framework\Assert;

trait AccountTrait
{
    /**
     * @Given /^an account already exists with the email$/
     */
    public function anAccountAlreadyExistsWithTheEmail(): void
    {
        $this->container->accountGatewaySpy()->save(Account::createActiveAccount(
            new AccountId(self::existingAccountUuid()),
            new AccountUsername('Existing'),
            new AccountPassword('password'),
            new AccountEmail(self::emailToUse()),
        ));
    }

    /**
     * @When /^a client creates an account$/
     * @When /^a client creates an account with the same email$/
     */
    public function aClientCreatesAnAccount(): void
    {
        try {
            $response = $this->container->commandBus()->execute(
                SignupCommand::fromScalars(
                    id: self::newAccountUuid(),
                    username: 'User',
                    password: 'password',
                    email: self::emailToUse(),
                )
            );

            assert($response instanceof AccountId);

            $this->accountId = $response;
        } catch (AnAccountAlreadyUsesTheEmailException $e) {
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
            $this->container->accountGatewaySpy()->savedAccounts(),
        );
    }

    /**
     * @Then /^it did not register the new account$/
     */
    public function itDoesNotRegisterTheNewAccount(): void
    {
        Assert::assertNotContains(
            self::newAccountUuid(),
            $this->container->accountGatewaySpy()->savedAccounts(),
        );

        Assert::assertContainsEquals(
            self::existingAccountUuid(),
            $this->container->accountGatewaySpy()->savedAccounts(),
        );
    }

    private static function emailToUse(): string
    {
        return 'user@example.com';
    }

    private static function newAccountUuid(): string
    {
        return '51cefa3e-c223-469e-a23c-61a32e4bf048';
    }

    private static function existingAccountUuid(): string
    {
        return '255c03d2-4149-4fe2-b922-65ed3ce4be0e';
    }
}
