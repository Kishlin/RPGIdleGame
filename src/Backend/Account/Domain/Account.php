<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountIsActive;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Account extends AggregateRoot
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    private function __construct(
        private AccountId $accountId,
        private AccountUsername $accountUsername,
        private AccountPassword $accountPassword,
        private AccountEmail $accountEmail,
        private AccountIsActive $accountIsActive,
    ) {
    }

    public static function createActiveAccount(
        AccountId $accountId,
        AccountUsername $accountUsername,
        AccountPassword $accountPassword,
        AccountEmail $accountEmail,
    ): self {
        $accountIsActive = new AccountIsActive(true);

        $account = new self($accountId, $accountUsername, $accountPassword, $accountEmail, $accountIsActive);

        $account->record(new AccountCreatedDomainEvent(
            $account->accountId,
            $account->accountUsername,
            $account->accountEmail,
        ));

        return $account;
    }

    public function accountId(): AccountId
    {
        return $this->accountId;
    }

    public function accountEmail(): AccountEmail
    {
        return $this->accountEmail;
    }

    public function accountIsActive(): AccountIsActive
    {
        return $this->accountIsActive;
    }
}
