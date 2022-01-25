<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;

final class AccountProvider
{
    public static function activeAccount(): Account
    {
        return Account::createActiveAccount(
            new AccountId('3cea2acd-b525-4984-ba48-51642b7e8db1'),
            new AccountUsername('User'),
            new AccountPassword('password'),
            new AccountEmail('fresh.account@example.com'),
        );
    }
}
