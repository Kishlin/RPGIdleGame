<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountIsActive;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountSalt;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Account extends AggregateRoot
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    private function __construct(
        private AccountId $id,
        private AccountUsername $username,
        private AccountPassword $password,
        private AccountEmail $email,
        private AccountSalt $salt,
        private AccountIsActive $isActive,
    ) {
    }

    public static function createActiveAccount(
        AccountId $id,
        AccountUsername $username,
        AccountPassword $password,
        AccountEmail $email,
        AccountSalt $salt,
    ): self {
        $isActive = new AccountIsActive(true);

        $account = new self($id, $username, $password, $email, $salt, $isActive);

        $account->record(new AccountCreatedDomainEvent($account->id));

        return $account;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function email(): AccountEmail
    {
        return $this->email;
    }

    public function isActive(): AccountIsActive
    {
        return $this->isActive;
    }
}
