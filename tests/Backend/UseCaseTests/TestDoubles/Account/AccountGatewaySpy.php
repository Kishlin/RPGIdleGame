<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account;

use Kishlin\Backend\Account\Application\Signup\AccountWithEmailGateway;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\AccountReaderGateway;
use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use ReflectionProperty;

final class AccountGatewaySpy implements AccountGateway, AccountWithEmailGateway, AccountReaderGateway
{
    /** @var array<string, Account> */
    private array $accounts = [];

    public function save(Account $account): void
    {
        $this->accounts[$account->id()->value()] = $account;
    }

    public function findOneById(AccountId $accountId): ?Account
    {
        return $this->accounts[$accountId->value()] ?? null;
    }

    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool
    {
        $property = new ReflectionProperty(Account::class, 'email');

        foreach ($this->accounts as $account) {
            $other = $property->getValue($account);

            assert($other instanceof AccountEmail);

            if ($accountEmail->equals($other)) {
                return true;
            }
        }

        return false;
    }

    public function readModelForAuthentication(string $usernameOrEmail): ?AccountDetailsForAuthentication
    {
        foreach ($this->accounts as $account) {
            if (in_array($usernameOrEmail, [$account->email()->value(), $account->username()->value()], true)) {
                return AccountDetailsForAuthentication::fromScalars(
                    $account->id()->value(),
                    $account->password()->value(),
                    $account->email()->value(),
                );
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function savedAccounts(): array
    {
        return array_keys($this->accounts);
    }
}
