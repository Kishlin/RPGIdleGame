<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\RepositorySpy;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\AccountId;
use ReflectionException;
use ReflectionProperty;

final class AccountGatewaySpy implements AccountGateway
{
    /** @var array<string, Account> */
    private array $accounts = [];

    public function save(Account $account): void
    {
        $this->accounts[$account->accountId()->value()] = $account;
    }

    public function findOneById(AccountId $accountId): ?Account
    {
        return $this->accounts[$accountId->value()] ?? null;
    }

    /**
     * @throws ReflectionException
     */
    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool
    {
        $property = new ReflectionProperty(Account::class, 'accountEmail');

        foreach ($this->accounts as $account) {
            $other = $property->getValue($account);

            assert($other instanceof AccountEmail);

            if ($accountEmail->equals($other)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function savedAccounts(): array
    {
        return array_keys($this->accounts);
    }
}
