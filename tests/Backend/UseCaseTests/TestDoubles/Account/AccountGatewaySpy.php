<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account;

use Kishlin\Backend\Account\Application\Signup\AccountWithEmailGateway;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use ReflectionProperty;

final class AccountGatewaySpy implements AccountGateway, AccountWithEmailGateway
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