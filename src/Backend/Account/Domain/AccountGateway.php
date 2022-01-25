<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;

interface AccountGateway
{
    public function save(Account $account): void;

    public function findOneById(AccountId $accountId): ?Account;

    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool;
}
